<?php

namespace Testing\Functional;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Testing\Generator\GeneratesValues;
use Testing\TestCase;

abstract class FunctionalTest extends TestCase
{
    use GeneratesValues, DatabaseTransactions;

    /**
     * Close Mockery.
     */
    public function tearDown()
    {
        parent::tearDown();
        $this->generator()->reset();
    }
}
