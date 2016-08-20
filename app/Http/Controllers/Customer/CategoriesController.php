<?php

namespace ChingShop\Http\Controllers\Customer;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Modules\Catalogue\Domain\Product\ProductRepository;
use Illuminate\Contracts\View\Factory as ViewFactory;

/**
 * Class CategoriesController.
 */
class CategoriesController extends Controller
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
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function viewAction()
    {
        $products = $this->productRepository->loadLatest(500);

        return $this->viewFactory->make(
            'customer.product.category',
            compact('products')
        );
    }
}
