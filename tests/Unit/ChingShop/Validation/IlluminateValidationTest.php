<?php

namespace Testing\Unit\ChingShop\Validation;

use ChingShop\Validation\IlluminateValidation;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Testing\Unit\UnitTest;

class IlluminateValidationTest extends UnitTest
{
    /** @var IlluminateValidation */
    private $illuminateValidation;

    /** @var Factory|MockObject */
    private $validationFactory;

    /**
     * Set up illuminate validation with mock dependencies.
     */
    public function setUp()
    {
        parent::setUp();
        $this->validationFactory = $this->makeMock(Factory::class);
        $this->illuminateValidation = new IlluminateValidation(
            $this->validationFactory
        );
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(
            IlluminateValidation::class,
            $this->illuminateValidation
        );
    }

    /**
     * Should use validation factory to test given data.
     */
    public function testPasses()
    {
        $testData = ['foo' => 'bar'];
        $rules = ['foo' => 'rule'];

        $validation = $this->makeMock(Validator::class);
        $validation->expects($this->atLeastOnce())
            ->method('passes')
            ->willReturn(true);

        $this->validationFactory->expects($this->atLeastOnce())
            ->method('make')
            ->with($testData, $rules)
            ->willReturn($validation);

        $this->assertTrue(
            $this->illuminateValidation->passes($testData, $rules)
        );
    }

    /**
     * Validator should not have any messages before validation.
     */
    public function testMessagesAreEmptyBeforeValidation()
    {
        $this->assertEmpty($this->illuminateValidation->messages());
    }

    /**
     * Should pass on messages from validation.
     */
    public function testUsesValidationMessages()
    {
        $validation = $this->makeMock(Validator::class);

        $messages = ['foo message'];
        $validation->expects($this->atLeastOnce())
            ->method('passes')
            ->willReturn(true);
        $validation->expects($this->atLeastOnce())
            ->method('messages')
            ->willReturn($messages);

        $this->validationFactory->expects($this->atLeastOnce())
            ->method('make')
            ->willReturn($validation);

        $this->illuminateValidation->passes([], []);

        $this->assertEquals($messages, $this->illuminateValidation->messages());
    }
}
