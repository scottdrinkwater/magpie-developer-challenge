<?php

declare(strict_types= 1);

namespace App\Utils;

class ArrayHelper
{
    public static function stripos(string $haystack, array $needles, int $offset = 0): bool
    {
        foreach($needles as $needle) {
            if(stripos($haystack, $needle, $offset) !== false) {
                return true;
            }
        }
    
        return false;
    }
}