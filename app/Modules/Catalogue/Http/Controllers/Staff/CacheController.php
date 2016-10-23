<?php

namespace ChingShop\Modules\Catalogue\Http\Controllers\Staff;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Catalogue\Domain\CatalogueView;

/**
 * Staff user cache-management actions.
 *
 * @package ChingShop\Modules\Catalogue\Http\Controllers\Staff
 */
class CacheController extends Controller
{
    /** @var CatalogueView */
    private $view;

    /** @var WebUi */
    private $webUi;

    /**
     * @param CatalogueView $view
     * @param WebUi         $webUi
     */
    public function __construct(CatalogueView $view, WebUi $webUi)
    {
        $this->view = $view;
        $this->webUi = $webUi;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \BadMethodCallException
     */
    public function clearProductCache()
    {
        $this->view->clearAll();

        $this->webUi->successMessage('Product cache was cleared.');

        return $this->webUi->redirect('products.index');
    }
}
