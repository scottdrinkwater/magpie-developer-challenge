<?php

declare(strict_types= 1);

namespace App\Utils;

class ArrayHelper
{
    /**
     * Check if one of an array substring exists in a string (case insensitive)
     *
     * @param string $haystack
     * @param array $needles
     * @param integer $offset
     * @return boolean
     */
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