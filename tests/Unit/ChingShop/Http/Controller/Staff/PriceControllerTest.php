<?php

namespace Testing\Unit\ChingShop\Http\Controller\Staff;

use ChingShop\Modules\Catalogue\Http\Controllers\Staff\PriceController;
use Testing\Unit\ChingShop\Http\Controller\ControllerTest;

class PriceControllerTest extends ControllerTest
{
    /** @var \ChingShop\Modules\Catalogue\Http\Controllers\Staff\PriceController */
    private $priceController;

    /**
     * Set up the price controller for each test.
     */
    public function setUp()
    {
        $this->priceController = new PriceController(
            $this->productRepository(),
            $this->webUi()
        );
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(
            \ChingShop\Modules\Catalogue\Http\Controllers\Staff\PriceController::class, $this->priceController);
    }
}
