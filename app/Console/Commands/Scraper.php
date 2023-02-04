<?php

namespace App\Console\Commands;

use App\Jobs\ScraperJob;
use Illuminate\Console\Command;

class Scraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:scraper';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrap for books';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        dispatch(new ScraperJob());

        $this->line('Scraping done 🚨');
    }
}
