<?php

declare(strict_types=1);

namespace App\Mapper;
use App\Entity\Phone;
use Carbon\CarbonImmutable;
use Symfony\Component\DomCrawler\Crawler;
use App\Utils\ArrayHelper;

final class PhoneMapper extends BaseProductMapper
{
    /**
     * @param Crawler $page
     */
    public function __construct(private Crawler $page)
    {
        $this->page = $page;
    }

    /**
     * {@inheritDoc}
     * 
     * @return Phone
     */
    public function toEntity(): Phone
    {
        return new Phone(
            $this->getTitle(),
            $this->getPrice(),
            $this->getImage(),
            $this->getCapacity(),
            $this->getColours(),
            $this->getAvailabilityText(),
            $this->getIsAvailable(),
            $this->getShippingText(),
            $this->getShippingDate()
        );
    }

    /**
     * @return self
     */
    public static function fromCrawler(Crawler $page): self
    {
        return new self($page);
    }

    /**
     * @return string
     */
    private function getTitle(): string
    {
        return trim(
            $this->page
                ->filter(".product-name")
                ->text()
        );   
    }

    /**
     * @return float
     */
    private function getPrice(): float
    {
        $priceText = $this->findDivContainingText(self::CURRENCY_SYMBOLS)->text();
        $price = str_replace(self::CURRENCY_SYMBOLS, '', $priceText);

        return (float) trim($price);
    }

    /**
     * @return string
     */
    private function getImage(): string
    {
        return $this->page->filter('img')->attr('src');
    }

    /**
     * @return integer
     */
    private function getCapacity(): int
    {
        return (int) $this->page->filter('.product-capacity')->text() * 1000;
    }

    /**
     * @return string[]
     */
    private function getColours(): array
    {
        return $this->page->filter('span.rounded-full')->each(fn ($circle) => $circle->attr('data-colour'));
    }

    /**
     * @return string
     */
    private function getAvailabilityText(): string
    {
        $availabilityText =  $this->findDivContainingText(['Availability:'])->text();

        return trim(str_replace('Availability:', '', $availabilityText));
    }

    /**
     * @return boolean
     */
    private function getIsAvailable(): bool
    {
        return ArrayHelper::stripos($this->getAvailabilityText(), self::IS_IN_STOCK_IDENTIFIERS);
    }

    /**
     * @return string
     */
    private function getShippingText(): string
    {
        $node = $this->findDivContainingText(self::SHIPPING_TEXT_NODE_IDENTIFIERS);
        return $node->count() > 0 ? $node->text() : '';
    }

    /**
     * @return \DateTimeImmutable|null
     */
    private function getShippingDate(): ?\DateTimeImmutable
    {
        $dateText = $this->getShippingText();
        if (!$dateText) {
            return null;
        }
        $dateSegments = preg_split(self::EXTRACT_SHIPPING_DATE_PATTERN, $dateText);
        $lastDateSegmentsIndex = count($dateSegments) - 1;
        $dateString = trim($dateSegments[$lastDateSegmentsIndex] ?? '');
        try {
            return $dateString ? CarbonImmutable::parse($dateString) : null;
        } catch (\Exception $e) {   
            return null;
        }
    }

    /**
     * Find a node on the page containing the provided text.
     *
     * @param  string[] $text
     * @return Crawler
     */
    private function findDivContainingText(array $text): Crawler
    {
        return $this->page
            ->filter("div div div")
            ->reduce(fn (Crawler $node) => ArrayHelper::stripos($node->text(), $text));
    }
}