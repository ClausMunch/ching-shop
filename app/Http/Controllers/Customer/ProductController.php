<?php

namespace ChingShop\Http\Controllers\Customer;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory as ViewFactory;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Catalogue\Product\ProductRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    /** @var ProductRepository */
    private $productRepository;

    /** @var ViewFactory */
    private $viewFactory;

    /** @var ResponseFactory */
    private $responseFactory;

    /**
     * ProductController constructor.
     * @param ProductRepository $productRepository
     * @param ViewFactory $viewFactory
     * @param ResponseFactory $responseFactory
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
     * @param int $ID
     * @param string $slug
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function viewAction(int $ID, string $slug)
    {
        $product = $this->productRepository->presentByID($ID);
        if (!$product->ID()) {
            throw new NotFoundHttpException;
        }
        if (!$product->slug() === $slug) {
            return $this->responseFactory->redirectToRoute(
                'product::view',
                [
                    'id'   => $product->ID(),
                    'slug' => $product->slug()
                ],
                301
            );
        }
        return $this->viewFactory->make(
            'customer.product.view',
            compact('product')
        );
    }
}