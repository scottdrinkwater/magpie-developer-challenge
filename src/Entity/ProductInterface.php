<?php

declare(strict_types=1);

namespace App\Entity;

interface ProductInterface
{
    /**
     * Get a hash which identifies whether or not this product is unique.
     *
     * @param string $colour
     * @return string
     */
    public function getUniqueHash(string $colour): string;
}