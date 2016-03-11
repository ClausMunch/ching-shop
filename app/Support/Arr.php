<?php

namespace ChingShop\Support;

use Illuminate\Support\Arr as IlluminateArr;

class Arr extends IlluminateArr
{
    /**
     * @param array $whole
     * @param int   $pieces
     *
     * @return array[]
     */
    public static function partition(array $whole, int $pieces): array
    {
        $wholeCount = count($whole);
        $parts = [];
        for ($i = 0; $i < $wholeCount; ++$i) {
            $partIndex = $i % $pieces;
            $parts[$partIndex][] = $whole[$i];
        }

        return $parts;
    }
}
