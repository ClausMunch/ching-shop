<?php

namespace Testing;

trait TestUtil
{
    /**
     * @param int      $times
     * @param callable $action
     *
     * @return array
     */
    private function repeat(int $times, callable $action): array
    {
        $results = [];
        for ($i = 0; $i < $times; $i++) {
            $results[] = $action();
        }

        return $results;
    }
}
