<?php

namespace Testing\Unit\Behaviour;

use Illuminate\Database\Eloquent\Model;
use Mockery\MockInterface;
use PHPUnit_Framework_TestCase as TestCase;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;

trait MocksModel
{
    /** @var Model|MockInterface */
    private $mockModel;

    /**
     * @param MockInterface $mockModel
     */
    protected function setMockModel(MockInterface $mockModel)
    {
        $this->mockModel = $mockModel;
    }

    /**
     * @param string $attributeName
     * @param mixed $value
     */
    protected function mockModelAttribute(string $attributeName, $value)
    {
        $this->mockModel()->shouldReceive('getAttribute')
            ->with($attributeName)
            ->zeroOrMoreTimes()
            ->andReturn($value);
    }

    /**
     * @param string $methodName
     * @param mixed $value
     */
    protected function mockModelMethod(string $methodName, $value = null)
    {
        $this->mockModel()->shouldReceive($methodName)
            ->zeroOrMoreTimes()
            ->andReturn($value);
    }

    /**
     * @param TestCase $testCase
     * @return Model|MockInterface
     */
    private function mockModel(TestCase $testCase = null): MockInterface
    {
        if (!isset($this->mockModel)) {
            $this->mockModel = new MockBuilder(
                $testCase ? $testCase : $this,
                Model::class
            );
        }
        return $this->mockModel;
    }
}
