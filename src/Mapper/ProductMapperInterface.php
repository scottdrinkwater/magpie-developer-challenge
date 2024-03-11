<?php

declare(strict_types= 1);

namespace App\Mapper;
use App\Entity\ProductInterface;
use Symfony\Component\DomCrawler\Crawler;

interface ProductMapperInterface {
    public function __construct(Crawler $page);
    public function toEntity(): ProductInterface;
    public static function fromCrawler(Crawler $page): self;
}