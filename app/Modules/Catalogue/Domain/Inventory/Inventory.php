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
        $stockItem = $this->stockItemResource
            ->where('product_option_id', '=', $productOption->id)
            ->has('orderItem', '<', 1)
            ->first();

        if (!$stockItem) {
            return new StockItem();
        }

        return $stockItem;
    }
}
