<?php

declare(strict_types= 1);

namespace App\Mapper;
use App\Entity\ProductInterface;
use Symfony\Component\DomCrawler\Crawler;

abstract class BaseProductMapper
{
    protected const IS_IN_STOCK_IDENTIFIERS = ['In Stock'];
    protected const SHIPPING_TEXT_NODE_IDENTIFIERS = ['deliver', 'available', 'shipping'];
    protected const EXTRACT_SHIPPING_DATE_PATTERN = '/(on|by|from|Delivers|Delivery) /';
    protected const CURRENCY_SYMBOLS = ['£', '$'];


    /**
     * @param Crawler $page
     */
    public abstract function __construct(Crawler $page);
    
    /**
     * Coverts the Crawler instance containing the product div into an entity.
     * @return ProductInterface
     */
    public abstract function toEntity(): ProductInterface;

    /**
     * @param Crawler $page
     * @return self
     */
    public abstract static function fromCrawler(Crawler $page): self;
}