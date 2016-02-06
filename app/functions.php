<?php

if (!function_exists('array_partition')) {

    /**
     * @param array $whole
     * @param int $pieces
     * @return array[]
     */
    function array_partition(array $whole, int $pieces): array
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
