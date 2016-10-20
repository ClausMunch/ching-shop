<?php

namespace ChingShop\Http\Controllers\Customer;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Modules\Catalogue\Domain\CatalogueView;
use ChingShop\Modules\Catalogue\Domain\Product\ProductRepository;
use Illuminate\Contracts\Routing\ResponseFactory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ProductController.
 */
class ProductController extends Controller
{
    /** @var ProductRepository */
    private $productRepository;

    /** @var CatalogueView */
    private $view;

    /** @var ResponseFactory */
    private $responseFactory;

    /**
     * ProductController constructor.
     *
     * @param ProductRepository $productRepository
     * @param CatalogueView     $view
     * @param ResponseFactory   $responseFactory
     */
    public function __construct(
        ProductRepository $productRepository,
        CatalogueView $view,
        ResponseFactory $responseFactory
    ) {
        $this->productRepository = $productRepository;
        $this->view = $view;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param int    $productId
     * @param string $slug
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function viewAction(int $productId, string $slug)
    {
        $product = $this->productRepository->loadAlone($productId);
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

        return $this->view->make(
            'customer.product.view',
            [
                'body' => $this->view->productBody($product),
                'meta' => $this->view->productMeta($product),
            ]
        );
    }
}
