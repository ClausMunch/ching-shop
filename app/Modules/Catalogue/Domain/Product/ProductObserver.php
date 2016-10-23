<?php

namespace ChingShop\Modules\Catalogue\Domain\Product;

use ChingShop\Modules\Catalogue\Domain\CatalogueView;

/**
 * Observe events on product models.
 */
class ProductObserver
{
    /** @var CatalogueView */
    private $catalogueView;

    /**
     * ProductObserver constructor.
     *
     * @param CatalogueView $catalogueView
     */
    public function __construct(CatalogueView $catalogueView)
    {
        $this->catalogueView = $catalogueView;
    }

    /**
     * Clean catalogue view of the product on save.
     *
     * @param Product $product
     */
    public function saved(Product $product)
    {
        $this->catalogueView->clearProduct($product->id);
    }
}
