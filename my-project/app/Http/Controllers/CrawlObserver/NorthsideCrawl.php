<?php

namespace App\Http\Controllers\CrawlObserver;

use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Symfony\Component\DomCrawler\Crawler;
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
        $crawler = new Crawler($response->getBody());

        $label = $crawler->filter('#mien-bac > table > thead')->text();
        $getTime = substr($label, -10);
        $tdTags = $crawler->filter('#mien-bac > table > tbody > tr > td');

        // filter prize nodes
        $prizeNodes = $tdTags->reduce(function (Crawler $node, $i) {
            return ($i % 2) == 0;
        });
        // get prizes
        $prizes = $prizeNodes->each(function (Crawler $node, $i) {
            return $node->text();
        });

        // filter result nodes
        $resultNodes = $tdTags->reduce(function (Crawler $node, $i) {
            return ($i % 2) != 0;
        });
        // get result groups
        $groups = array_column($resultNodes->filter('div > span')->each(function (Crawler $node, $i) {
            return $node->evaluate('substring-after(@data-prize, "")');
        }), '0');
        $results = $resultNodes->filter('div > span')->each(function (Crawler $node, $i) {
            return $node->text();
        });
        $combineResults = [];
        foreach ($results as $key => $value) {
            if (array_key_exists($groups[$key], $combineResults)) {
                $combineResults[$groups[$key]] .= "," .$results[$key];
            }
            else {
                $combineResults[$groups[$key]] = $results[$key];
            }
        }

        dd($combineResults);
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
