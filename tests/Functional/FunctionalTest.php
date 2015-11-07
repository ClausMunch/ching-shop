<?php

namespace Testing\Functional;

use Testing\TestCase;
use Testing\Generator\GeneratesValues;

use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class FunctionalTest extends TestCase
{
    use GeneratesValues, DatabaseTransactions;

    /**
     * Close Mockery
     */
    public function tearDown()
    {
        parent::tearDown();
        $this->generator()->reset();
    }
}
