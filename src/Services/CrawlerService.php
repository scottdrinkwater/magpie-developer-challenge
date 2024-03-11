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
    public function __construct(
        private Client $client,
        private string $url
    ) {
        $this->client = $client;
    }

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