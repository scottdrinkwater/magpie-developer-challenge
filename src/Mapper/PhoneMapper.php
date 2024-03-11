<?php

declare(strict_types=1);

namespace App\Mapper;
use App\Entity\Phone;
use Carbon\CarbonImmutable;
use Symfony\Component\DomCrawler\Crawler;
use App\Utils\ArrayHelper;

final class PhoneMapper implements ProductMapperInterface
{
    public function __construct(private Crawler $page)
    {
        $this->page = $page;
    }

    /**
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

    private function getTitle(): string
    {
        return trim(
            $this->page
                ->filter(".product-name")
                ->text()
        );   
    }

    private function getPrice(): float
    {
        // To make more extendable should search an array of potential currencies.
        $currencySymbol = '£';
        $priceText = $this->findDivContainingText([$currencySymbol])->text();
        $price = str_replace($currencySymbol, '', $priceText);

        return (float) trim($price);
    }

    private function getImage(): string
    {
        return $this->page->filter('img')->attr('src');
    }

    private function getCapacity(): int
    {
        return (int) $this->page->filter('.product-capacity')->text() * 1000;
    }

    private function getColours(): array
    {
        return $this->page->filter('span.rounded-full')->each(fn ($circle) => $circle->attr('data-colour'));
    }

    private function getAvailabilityText(): string
    {
        $availabilityText =  $this->findDivContainingText(['Availability:'])->text();

        return trim(str_replace('Availability:', '', $availabilityText));
    }

    private function getIsAvailable(): bool
    {
        return stripos($this->getAvailabilityText(), 'In Stock') !== false;
    }

    private function getShippingText(): string
    {
        $node = $this->findDivContainingText(['deliver', 'available', 'shipping']);
        return $node->count() > 0 ? $node->text() : '';
    }

    private function getShippingDate(): ?\DateTimeImmutable
    {
        $dateText = $this->getShippingText();
        if (!$dateText) {
            return null;
        }
        $dateString = trim(preg_split('/ (on|by|from) /', $dateText)[1] ?? '');
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