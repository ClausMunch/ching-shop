<?php

namespace Testing\Unit\ChingShop\Http\Controller;

use ChingShop\Catalogue\CatalogueRepository;
use ChingShop\Catalogue\Product\ProductRepository;
use ChingShop\Http\WebUi;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Laracasts\Flash\FlashNotifier;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Testing\Unit\UnitTest;

abstract class ControllerTest extends UnitTest
{
    /** @var ViewFactory */
    private $viewFactory;

    /** @var ResponseFactory */
    private $responseFactory;

    /** @var FlashNotifier */
    private $flashNotifier;

    /** @var ProductRepository|MockObject */
    private $productRepository;

    /** @var WebUi|MockObject */
    private $webUi;

    /** @var CatalogueRepository|MockObject */
    private $catalogueRepository;

    /**
     * Unset view factory after each test.
     */
    public function tearDown()
    {
        parent::tearDown();

        unset($this->viewFactory);
        unset($this->responseFactory);
        unset($this->productRepository);
        unset($this->flashNotifier);
        unset($this->webUi);
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
     * @return FlashNotifier|MockObject
     */
    protected function flashNotifier()
    {
        if (empty($this->flashNotifier)) {
            $this->flashNotifier = $this->makeMock(FlashNotifier::class);
        }

        return $this->flashNotifier;
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

    /**
     * @return WebUi|MockObject
     */
    protected function webUi()
    {
        if (empty($this->webUi)) {
            $this->webUi = $this->makeMock(WebUi::class);
        }

        return $this->webUi;
    }

    /**
     * @return CatalogueRepository|MockObject
     */
    protected function catalogueRepository()
    {
        if (empty($this->catalogueRepository)) {
            $this->catalogueRepository = $this->makeMock(
                CatalogueRepository::class
            );
        }

        return $this->catalogueRepository;
    }

    /**
     * @return View|MockObject
     */
    protected function makeMockView()
    {
        return $this->makeMock(View::class);
    }
}
