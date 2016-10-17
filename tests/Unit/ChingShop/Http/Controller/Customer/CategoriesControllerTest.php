<?php

namespace Testing\Unit\ChingShop\Http\Controller\Customer;

use ChingShop\Http\Controllers\Customer\CategoriesController;
use Testing\Unit\ChingShop\Http\Controller\ControllerTest;

class CategoriesControllerTest extends ControllerTest
{
    /** @var CategoriesController */
    private $categoriesController;

    /**
     * Set up categories controller for each test.
     */
    public function setUp()
    {
        $this->categoriesController = new CategoriesController(
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
        $this->assertInstanceOf(
            CategoriesController::class,
            $this->categoriesController
        );
    }
}
