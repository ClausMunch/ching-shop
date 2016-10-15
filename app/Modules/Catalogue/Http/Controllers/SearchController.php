<?php

namespace ChingShop\Modules\Catalogue\Http\Controllers;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use Illuminate\Http\Request;

/**
 * Handles customer catalogue search methods.
 */
class SearchController extends Controller
{
    /** @var Product */
    private $productResource;

    /** @var WebUi */
    private $webUi;

    /**
     * SearchController constructor.
     *
     * @param Product $productResource
     * @param WebUi   $webUi
     */
    public function __construct(Product $productResource, WebUi $webUi)
    {
        $this->productResource = $productResource;
        $this->webUi = $webUi;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function searchAction(Request $request)
    {
        return $this->webUi->view(
            'customer.product.search',
            [
                'query'    => $request->get('q'),
                'products' => $this->productResource->search(
                    $request->get('q')
                )->paginate(),
            ]
        );
    }
}
