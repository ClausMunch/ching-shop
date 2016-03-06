<?php

namespace Testing\Unit\ChingShop\Http\Controller\Customer;

use ChingShop\Http\Controllers\Customer\RootController;
use Testing\Unit\ChingShop\Http\Controller\ControllerTest;

class RootControllerTest extends ControllerTest
{
    /** @var RootController */
    private $rootController;

    /**
     * Set up root controller with mock dependencies.
     */
    public function setUp()
    {
        parent::setUp();

        $this->rootController = new RootController(
            $this->productRepository(),
            $this->viewFactory(),
            $this->responseFactory()
        );
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(RootController::class, $this->rootController);
    }

    /**
     * Should be able to get index page.
     */
    public function testGetIndex()
    {
        $this->productRepository()->expects($this->once())
            ->method('presentLatest')
            ->with($this->isType('int'));

        $this->viewFactory()->expects($this->once())
            ->method('make')
            ->with(
                $this->isType('string'),
                $this->isType('array')
            );

        $this->rootController->getIndex();
    }
}