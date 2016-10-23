<?php

namespace ChingShop\Http\Controllers\Customer;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Modules\Catalogue\Domain\CatalogueView;

/**
 * Class RootController.
 */
class RootController extends Controller
{
    /** @var CatalogueView */
    private $view;

    /**
     * @param CatalogueView $view
     */
    public function __construct(CatalogueView $view)
    {
        $this->view = $view;
    }

    /**
     * @throws \BadMethodCallException
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex()
    {
        return $this->view->make(
            'welcome',
            [
                'productRows' => $this->view->frontProducts()->chunk(4),
            ]
        );
    }
}
