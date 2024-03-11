<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Phone;
use App\Mapper\PhoneMapper;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Service to extract product data from a webpage.
 */
final class PhoneScraperService extends BaseScraperService
{
    public function getPageCount(): int
    {
        return $this->page->filter('#pages a')->count();
    }

    /**
     * Get an array of phone entities.
     *
     * @return Phone[]
     */
    public function getEntities(): array
    {
        return $this->page
            ->filter('.product')
            ->each(fn (Crawler $node) => PhoneMapper::fromCrawler($node)->toEntity());
    }
}