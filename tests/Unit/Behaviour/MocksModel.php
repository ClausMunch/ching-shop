<?php

namespace Testing\Unit\Behaviour;

use Mockery;
use Mockery\MockInterface;
use Illuminate\Database\Eloquent\Model;

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
     * @param $value
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
     * @return Model|MockInterface
     */
    private function mockModel(): MockInterface
    {
        if (!isset($this->mockModel)) {
            $this->mockModel = Mockery::mock(Model::class);
        }
        return $this->mockModel;
    }
}
