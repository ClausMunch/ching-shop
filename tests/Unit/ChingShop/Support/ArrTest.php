<?php

namespace Testing\Unit\ChingShop\Support;

use ChingShop\Support\Arr;
use Testing\Unit\UnitTest;

class ArrTest extends UnitTest
{
    /**
     * Should be able to partition an array.
     */
    public function testPartition()
    {
        $orig = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];
        $partitioned = Arr::partition($orig, 2);
        $this->assertEquals(
            [
                ['a', 'c', 'e', 'g'],
                ['b', 'd', 'f', 'h'],
            ],
            $partitioned
        );
    }
}
