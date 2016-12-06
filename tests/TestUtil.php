<?php

namespace Testing;

use Illuminate\Support\Collection;

trait TestUtil
{
    /**
     * @param int      $times
     * @param callable $action
     *
     * @return Collection
     */
    private function repeat(int $times, callable $action): Collection
    {
        $results = new Collection();
        for ($i = 0; $i < $times; $i++) {
            $results->push($action());
        }

        return $results;
    }
}
