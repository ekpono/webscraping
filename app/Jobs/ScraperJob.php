<?php

namespace App\Jobs;

use App\Models\Book;
use Goutte\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ScraperJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    CONST TOTALPAGE = 10;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $counter = 1;

        try {
            while ($counter <= self::TOTALPAGE) {
                $this->scrap($counter);

                $counter++;
            }
        }catch (\Exception $exception) {
            Log::alert(json_encode(['SCRAPPING ERROR: '.$exception]));
        }
    }

    public function scrap(int $page)
    {
        $httpClient = new Client();
        $response = $httpClient->request('GET', "https://books.toscrape.com/catalogue/page-$page.html");

        $bookPrices = $this->getBookPrices($response);
        $bookTitles = $this->getBookTitles($response);
        $bookAvailability = $this->getBookAvailability($response);
        $bookImageUrls = $this->getBookImageUrl($response);

        for ($i = 0; $i <= count($bookPrices) -1; $i++) {
            $title = $bookTitles[$i];
            $price = $bookPrices[$i];
            $availability = $bookAvailability[$i];
            $imageUrl = $bookImageUrls[$i];

            Book::create([
                'title' => $title,
                'price' => $price,
                'availability' => $availability,
                'image_url' => $imageUrl
            ]);
        }
    }

    private function getBookPrices($response)
    {
        return $response->filter('.row li article div.product_price p.price_color')->each(function ($node) {
            return $node->text();
        });
    }

    private function getBookTitles($response)
    {
        $books = $response->filter('.row li article h3 a');
        return $books->each(function ($node) {
            return $node->text();
        });
    }

    private function getBookAvailability($response)
    {
        $books = $response->filter('.row li article .product_price .availability');
        return $books->each(function ($node) {
            return $node->text();
        });
    }

    private function getBookImageUrl($response)
    {
        $books = $response->filter('.row li article > .image_container a img');
        return $books->each(function ($node) {
            $imageLink = $node->extract(['src'])[0];
            return 'https://books.toscrape.com/'. Str::substr($imageLink, 3);
        });
    }
}
