<?php

declare(strict_types= 1);

namespace App\Mapper;
use App\Entity\ProductInterface;
use Symfony\Component\DomCrawler\Crawler;

interface ProductMapperInterface
{
    /**
     * @param Crawler $page
     */
    public function __construct(Crawler $page);
    
    /**
     * Coverts the Crawler instance containing the product div into an entity.
     * @return ProductInterface
     */
    public function toEntity(): ProductInterface;

    /**
     * @param Crawler $page
     * @return self
     */
    public static function fromCrawler(Crawler $page): self;
}