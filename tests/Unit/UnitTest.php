<?php

namespace Testing\Unit;

use Testing\TestCase;

use Mockery;
use Mockery\MockInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

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
    protected function mockery(string $className): MockInterface
    {
        return Mockery::mock($className);
    }

    /**
     * @param string $className
     * @return MockObject
     */
    protected function makeMock(string $className): MockObject
    {
        return $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Allow a mock object to present a "fluent interface" of chained method
     * calls that returns a given value at the end
     * Works with magic method calls and real method calls
     * @param MockObject $mockObj
     * @param string $finalMethod
     * @param mixed $returnValue
     */
    protected function mockDemeterChain(
        MockObject $mockObj,
        string $finalMethod,
        $returnValue
    ) {
        $lastCall = '';
        $mockObj->expects($this->atLeastOnce())
            ->method($this->callback(
                function (string $methodName) use (&$lastCall) {
                    $lastCall = $methodName;
                    return true;
                }
            ))
            ->willReturnCallback(
                function (string $arg) use (
                    &$lastCall,
                    $finalMethod,
                    $mockObj,
                    $returnValue
                ) {
                    // Use the first argument as the method name if last call
                    // was magic
                    if ($lastCall === '__call') {
                        return $arg === $finalMethod ? $returnValue : $mockObj;
                    }
                    return $lastCall === $finalMethod ? $returnValue : $mockObj;
                }
            );
    }
}
