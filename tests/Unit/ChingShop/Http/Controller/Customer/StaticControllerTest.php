<?php

namespace Testing\Unit\ChingShop\Http\Controller\Customer;

use ChingShop\Http\Controllers\Customer\StaticController;
use Testing\Unit\ChingShop\Http\Controller\ControllerTest;

class StaticControllerTest extends ControllerTest
{
    /** @var StaticController */
    private $staticController;

    /**
     * Set up static controller with mock dependencies.
     */
    public function setUp()
    {
        parent::setUp();

        $this->staticController = new StaticController(
            $this->viewFactory(),
            $this->responseFactory()
        );
    }

    /**
     * Sanity check for instantiation
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(
            StaticController::class,
            $this->staticController
        );
    }

    /**
     * Should be able to get a static page
     */
    public function testPageAction()
    {
        $this->viewFactory()->expects($this->once())
            ->method('exists')
            ->with($this->isType('string'))
            ->willReturn('true');

        $this->viewFactory()->expects($this->once())
            ->method('make')
            ->with($this->isType('string'))
            ->willReturn($this->makeMockView());

        $this->staticController->pageAction($this->generator()->anySlug());
    }
}
