<?php

namespace Testing\Unit;

use Testing\TestCase;

use Mockery;
use Mockery\MockInterface;

use Testing\Generator\GeneratesValues;

abstract class UnitTest extends TestCase
{
    use GeneratesValues;

    /**
     * Close Mockery
     */
    public function tearDown()
    {
        parent::tearDown();
        Mockery::close();
        $this->generator()->reset();
    }

    /**
     * @param string $className
     * @return MockInterface
     */
    protected function makeMock(string $className): MockInterface
    {
        return Mockery::mock($className);
    }
}
