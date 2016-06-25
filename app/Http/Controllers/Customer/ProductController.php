<?php

namespace ChingShop\Http\Controllers\Customer;

use ChingShop\Catalogue\Product\ProductRepository;
use ChingShop\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory as ViewFactory;
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
     * @param int    $id
     * @param string $slug
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function viewAction(int $id, string $slug)
    {
        $product = $this->productRepository->loadById($id);
        if (!$product->id) {
            throw new NotFoundHttpException();
        }
        if ($product->slug !== $slug) {
            return $this->responseFactory->redirectToRoute(
                'product::view',
                [
                    'id'   => $product->id,
                    'slug' => $product->slug,
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
