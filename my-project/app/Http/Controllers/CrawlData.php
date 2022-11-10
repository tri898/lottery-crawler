<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CrawlObserver\NorthsideCrawl;
use Spatie\Crawler\Crawler;

class CrawlData extends Controller
{
    public function call()
    {
        $url = 'https://www.kqxs.vn';
        Crawler::create()
            ->setCrawlObserver(new NorthsideCrawl)
            ->startCrawling($url);
    }
}
