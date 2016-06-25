<?php

namespace Testing\Unit\ChingShop\Http\Controller\Customer;

use ChingShop\Http\Controllers\Customer\CategoriesController;
use Illuminate\Database\Eloquent\Collection;
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

    /**
     * Should retrieve products and pass to view factory.
     */
    public function testViewAction()
    {
        $products = new Collection(['foo product']);

        $this->productRepository()
            ->expects($this->atLeastOnce())
            ->method('loadLatest')
            ->willReturn($products);

        $view = 'foo view';
        $this->viewFactory()
            ->expects($this->atLeastOnce())
            ->method('make')
            ->with(
                'customer.product.category',
                [
                    'products' => $products,
                ]
            )
            ->willReturn($view);

        $response = $this->categoriesController->viewAction();

        $this->assertEquals($view, $response);
    }
}
