<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Jobs\ScraperJob;
use Goutte\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ScraperControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_scrapes_book_data()
    {
        Queue::fake();
        $job = new ScraperJob();
        $httpClient = \Mockery::mock(Client::class);
        $httpClient->shouldReceive('request')
            ->with('GET', 'https://books.toscrape.com/catalogue/page-1.html')
            ->andReturn(\Mockery::mock(\Symfony\Component\DomCrawler\Crawler::class, [
                'filter' => function () {
                    return collect([
                        (object) [
                            'text' => '£51.77'
                        ],
                        (object) [
                            'text' => '£53.74'
                        ],
                        (object) [
                            'text' => '£50.10'
                        ],
                    ]);
                },
            ]));

        $httpClient->shouldReceive('request')
            ->with('GET', 'https://books.toscrape.com/catalogue/page-2.html')
            ->andReturn(\Mockery::mock(\Symfony\Component\DomCrawler\Crawler::class, [
                'filter' => function () {
                    return collect([
                        (object) [
                            'text' => '£23.88'
                        ],
                        (object) [
                            'text' => '£25.19'
                        ],
                        (object) [
                            'text' => '£21.79'
                        ],
                    ]);
                },
            ]));

        $bookTitles = collect([
            '#HigherSelfie: Wake Up Your ...',
            '(Un)Qualified: How God Uses ...',
            'A Court of Thorns ...',
        ]);

        $bookAvailability = collect([
            'In stock',
            'In stock',
            'In stock',
        ]);

        $job->handle($httpClient);

        $this->assertCount(200, Book::all());
        $this->assertEquals($bookTitles, Book::pluck('title')->take(3));
        $this->assertEquals(['£23.11', '£54.00', '£52.37'], Book::pluck('price')->take(3)->toArray());
        $this->assertEquals($bookAvailability, Book::pluck('availability')->take(3));
    }
}
