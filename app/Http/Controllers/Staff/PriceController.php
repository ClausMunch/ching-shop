<?php

namespace ChingShop\Http\Controllers\Staff;

use ChingShop\Catalogue\Product\ProductRepository;
use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\Requests\Staff\Catalogue\Product\SetPriceRequest;
use ChingShop\Http\WebUi;

/**
 * Class PriceController.
 */
class PriceController extends Controller
{
    /** @var ProductRepository */
    private $productRepository;

    /** @var WebUi */
    private $webUi;

    /**
     * ProductController constructor.
     *
     * @param ProductRepository $productRepository
     * @param WebUi             $webUi
     */
    public function __construct(
        ProductRepository $productRepository,
        WebUi $webUi
    ) {
        $this->productRepository = $productRepository;
        $this->webUi = $webUi;
    }

    /**
     * @param string          $sku
     * @param SetPriceRequest $setPriceRequest
     *
     * @return \Illuminate\Http\RedirectResponse
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

        return $this->webUi->redirect('staff.products.show', ['sku' => $sku]);
    }
}
