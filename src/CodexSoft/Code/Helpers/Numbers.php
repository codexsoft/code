<?php

namespace CodexSoft\Code\Helpers;

class Numbers
{

    /**
     * Generating UNIQUE Random Numbers within a range
     * https://stackoverflow.com/questions/5612656/generating-unique-random-numbers-within-a-range-php
     *
     * @param int $min
     * @param int $max
     * @param int $quantity
     *
     * @return int[]
     */
    public static function uniqueRandomNumbersWithinRange(int $min, int $max, int $quantity): array
    {
        $numbers = range($min, $max);
        shuffle($numbers);
        return \array_slice($numbers, 0, $quantity);
    }

    /**
     * Calculate a random floating-point number
     * http://php.net/manual/en/function.mt-getrandmax.php
     *
     * @param int|float $min
     * @param int|float $max
     *
     * @return float
     */
    public static function randomFloat($min = 0, $max = 1): float
    {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }

}