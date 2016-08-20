<?php

namespace ChingShop\Http\Controllers\Customer;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Modules\Catalogue\Domain\Product\ProductRepository;
use Illuminate\Contracts\View\Factory as ViewFactory;

/**
 * Class RootController.
 */
class RootController extends Controller
{
    /** @var ProductRepository */
    private $productRepository;

    /** @var ViewFactory */
    private $viewFactory;

    /**
     * ProductController constructor.
     *
     * @param ProductRepository $productRepository
     * @param ViewFactory       $viewFactory
     */
    public function __construct(
        ProductRepository $productRepository,
        ViewFactory $viewFactory
    ) {
        $this->productRepository = $productRepository;
        $this->viewFactory = $viewFactory;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex()
    {
        $productRows = $this->productRepository->loadLatest(8)->chunk(4);

        return $this->viewFactory->make('welcome', compact('productRows'));
    }
}
