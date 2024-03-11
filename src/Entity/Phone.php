<?php

declare(strict_types=1);

namespace App\Entity;

class Phone implements ProductInterface
{
    public function __construct(
        public string $title, 
        public float $price, 
        public string $imageUrl, 
        public int $capacityMB, 
        public array $colours, 
        public string $availabilityText, 
        public bool $isAvailable, 
        public ?string $shippingText,
        public ?\DateTimeImmutable $shippingDate
    ) {
        $this->title = $title;
        $this->price = $price;
        $this->imageUrl = $imageUrl;
        $this->capacityMB = $capacityMB;
        $this->colours = $colours;
        $this->availabilityText = $availabilityText;
        $this->isAvailable = $isAvailable;
        $this->shippingText = $shippingText;
        $this->shippingDate = $shippingDate;
    }

    public function getUniqueHash(string $colour): string
    {
        return sprintf(
            '%s-%d-%d-%s',
            $this->title,
            $this->capacityMB,
            $this->price,
            $colour
        );
    }
}