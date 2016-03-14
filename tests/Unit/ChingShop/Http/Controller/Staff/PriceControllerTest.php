<?php

namespace Testing\Unit\ChingShop\Http\Controller\Staff;

use ChingShop\Http\Controllers\Staff\PriceController;
use Testing\Unit\ChingShop\Http\Controller\ControllerTest;

class PriceControllerTest extends ControllerTest
{
    /** @var PriceController */
    private $priceController;

    /**
     * Set up the price controller for each test.
     */
    public function setUp()
    {
        $this->priceController = new PriceController(
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
        $this->assertInstanceOf(PriceController::class, $this->priceController);
    }
}
