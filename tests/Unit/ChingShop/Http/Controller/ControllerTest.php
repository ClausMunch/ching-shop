<?php

namespace Testing\Unit\ChingShop\Http\Controller;

use ChingShop\Catalogue\Product\ProductRepository;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory as ViewFactory;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Testing\Unit\UnitTest;

abstract class ControllerTest extends UnitTest
{
    /** @var ViewFactory */
    private $viewFactory;

    /** @var ResponseFactory */
    private $responseFactory;

    /** @var ProductRepository|MockObject */
    private $productRepository;

    /**
     * Unset view factory after each test.
     */
    public function tearDown()
    {
        parent::tearDown();
        unset($this->viewFactory);
        unset($this->responseFactory);
    }

    /**
     * @return ViewFactory|MockObject
     */
    protected function viewFactory()
    {
        if (empty($this->viewFactory)) {
            $this->viewFactory = $this->makeMock(ViewFactory::class);
        }

        return $this->viewFactory;
    }

    /**
     * @return ResponseFactory|MockObject
     */
    protected function responseFactory()
    {
        if (empty($this->responseFactory)) {
            $this->responseFactory = $this->makeMock(ResponseFactory::class);
        }

        return $this->responseFactory;
    }

    /**
     * @return ProductRepository|MockObject
     */
    protected function productRepository()
    {
        if (empty($this->productRepository)) {
            $this->productRepository = $this->makeMock(
                ProductRepository::class
            );
        }

        return $this->productRepository;
    }
}