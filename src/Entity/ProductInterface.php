<?php

declare(strict_types=1);

namespace App\Entity;

interface ProductInterface {
    public function getUniqueHash(string $colour): string;
}