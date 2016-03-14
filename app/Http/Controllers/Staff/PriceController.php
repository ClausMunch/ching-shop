<?php

namespace ChingShop\Http\Controllers\Staff;

use ChingShop\Catalogue\Product\ProductRepository;
use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\Requests\SetPriceRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory as ViewFactory;

class PriceController extends Controller
{
    /** @var ProductRepository */
    private $productRepository;

    /** @var ViewFactory */
    private $viewFactory;

    /** @var ResponseFactory */
    private $responseFactory;

    /**
     * ProductController constructor.
     *
     * @param ProductRepository $productRepository
     * @param ViewFactory       $viewFactory
     * @param ResponseFactory   $responseFactory
     */
    public function __construct(
        ProductRepository $productRepository,
        ViewFactory $viewFactory,
        ResponseFactory $responseFactory
    ) {
        $this->productRepository = $productRepository;
        $this->viewFactory = $viewFactory;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param string          $sku
     * @param SetPriceRequest $setPriceRequest
     */
    public function setProductPrice(
        string $sku,
        SetPriceRequest $setPriceRequest
    ) {
        $this->productRepository->setPriceBySku(
            $sku,
            $setPriceRequest->get('units'),
            $setPriceRequest->get('subunits')
        );

        return $this->responseFactory->redirectToRoute(
            'staff.products.show',
            ['sku' => $sku]
        );
    }
}
