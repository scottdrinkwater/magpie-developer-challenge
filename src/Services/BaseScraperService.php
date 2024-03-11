<?php

declare(strict_types=1);

namespace App\Services;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Service to extract data from a webpage.
 */
abstract class BaseScraperService
{

    public function __construct(
        protected Crawler $page
    ) {
        $this->page = $page;
    }
}