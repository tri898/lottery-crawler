<?php

namespace App\Http\Controllers\CrawlObserver;

use DOMDocument;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class NorthsideCrawl extends CrawlObserver
{
    /**
     * Called when the crawler has crawled the given url successfully.
     *
     * @param UriInterface $url
     * @param ResponseInterface $response
     * @param UriInterface|null $foundOnUrl
     */
    public function crawled(
        UriInterface      $url,
        ResponseInterface $response,
        ?UriInterface     $foundOnUrl = null
    ): void {
        $doc = new DOMDocument();
        @$doc->loadHTML((string) $response->getBody());

//        return;
    }

    /**
     * Called when the crawler had a problem crawling the given url.
     *
     * @param UriInterface $url
     * @param RequestException $requestException
     * @param UriInterface|null $foundOnUrl
     */
    public function crawlFailed(
        UriInterface     $url,
        RequestException $requestException,
        ?UriInterface    $foundOnUrl = null
    ): void {
    }
}
