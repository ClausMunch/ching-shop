<?php

namespace ChingShop\Http\View\Customer;

use ChingShop\Modules\Catalogue\Domain\Product\ProductPresenter;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Router;

/**
 * Class LocationComposer.
 */
class LocationComposer
{
    const ROUTE_VIEW = 'view';

    /** @var Router */
    protected $router;

    /** @var UrlGenerator */
    protected $urlGenerator;

    /**
     * Bind a Location object to the view.
     *
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with(['location' => $this]);
    }

    /**
     * @param Router       $router
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(Router $router, UrlGenerator $urlGenerator)
    {
        $this->router = $router;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param Viewable $viewable
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function viewHrefFor(Viewable $viewable): string
    {
        return $this->urlGenerator->route(
            $viewable->routePrefix() . self::ROUTE_VIEW,
            $viewable->locationParts()
        );
    }

    /**
     * @param ProductPresenter $product
     *
     * @return string
     */
    public function productEnquiryMail(ProductPresenter $product): string
    {
        return 'mailto:ching@ching-shop.com?subject='
        . "I would like to buy '{$product->name()}' ({$product->sku()})";
    }
}
