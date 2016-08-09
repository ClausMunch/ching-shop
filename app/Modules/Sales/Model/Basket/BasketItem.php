<?php

namespace ChingShop\Modules\Sales\Model\Basket;

use ChingShop\Modules\Catalogue\Model\Price\Price;
use ChingShop\Modules\Catalogue\Model\Product\ProductOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use McCool\LaravelAutoPresenter\HasPresenter;

/**
 * @mixin \Eloquent
 *
 * @property int            $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property Basket         $basket
 * @property ProductOption  $productOption
 */
class BasketItem extends Model implements HasPresenter
{
    /**
     * A basket item belongs to a basket.
     *
     * @return BelongsTo
     */
    public function basket(): BelongsTo
    {
        return $this->belongsTo(Basket::class);
    }

    /**
     * A basket item points to a product option.
     *
     * @return BelongsTo
     */
    public function productOption(): BelongsTo
    {
        return $this->belongsTo(ProductOption::class);
    }

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return BasketItemPresenter::class;
    }

    /**
     * @return float
     */
    public function priceAsFloat(): float
    {
        /** @noinspection IsEmptyFunctionUsageInspection */
        if (empty($this->productOption->product->prices)) {
            return 0.0;
        }

        if (!$this->productOption->product->prices->first() instanceof Price) {
            return 0.0;
        }

        return $this->productOption->product->prices->first()->asFloat();
    }
}
