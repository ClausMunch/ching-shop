<?php

namespace ChingShop\Modules\Catalogue\Http\Controllers;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Catalogue\Http\Requests\SearchRequest;

/**
 * Handles customer catalogue search methods.
 */
class SearchController extends Controller
{
    const PAGE_SIZE = 15;

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
     * @param SearchRequest $request
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function searchAction(SearchRequest $request)
    {
        return $this->webUi->view(
            'customer.product.search',
            [
                'query'    => $request->searchQuery(),
                'products' => $this->productResource->search(
                    $request->searchQuery()
                )->paginate(self::PAGE_SIZE),
            ]
        );
    }
}
