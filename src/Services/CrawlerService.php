<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Service to crawl through pages to scrape.
 */
final class CrawlerService
{
    /**
     * @param Client $client
     * @param string $url
     */
    public function __construct(
        private Client $client,
        private string $url
    ) {
        $this->client = $client;
    }

    /**
     * @param integer $page
     * @return Crawler
     */
    public function fetchPage(int $page = 1): Crawler
    {
        $queryParams = '?page=' . $page;
        $response = $this->client->get($queryParams);

        return new Crawler(
            $response->getBody()->getContents(), 
            $this->url . $queryParams
        );
    }
}