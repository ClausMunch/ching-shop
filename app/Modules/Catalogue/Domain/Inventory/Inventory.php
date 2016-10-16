<?php

namespace ChingShop\Modules\Catalogue\Domain\Inventory;

use ChingShop\Modules\Catalogue\Domain\Product\ProductOption;

/**
 * Class Inventory.
 */
class Inventory
{
    /** @var StockItem */
    private $stockItemResource;

    /**
     * Inventory constructor.
     *
     * @param StockItem $stockItemResource
     */
    public function __construct(StockItem $stockItemResource)
    {
        $this->stockItemResource = $stockItemResource;
    }

    /**
     * @param ProductOption $productOption
     *
     * @return StockItem
     */
    public function allocate(ProductOption $productOption): StockItem
    {
        /** @var StockItem $stockItem */
        $stockItem = $this->stockItemResource
            ->available()
            ->where('product_option_id', '=', $productOption->id)
            ->first();

        if (!$stockItem instanceof StockItem) {
            return new StockItem();
        }

        return $stockItem;
    }
}
