<?php

namespace ChingShop\Modules\Sales\Domain\Basket;

use ChingShop\Modules\Catalogue\Domain\Price\Price;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Catalogue\Domain\Product\ProductOption;
use ChingShop\Modules\Sales\Domain\Offer\OfferComponent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
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
class BasketItem extends Model implements OfferComponent, HasPresenter
{
    use SoftDeletes;

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
        /* @noinspection IsEmptyFunctionUsageInspection */
        if (empty($this->productOption->product->prices)) {
            return 0.0;
        }

        if (!$this->productOption->product->prices->first() instanceof Price) {
            return 0.0;
        }

        return $this->productOption->product->prices->first()->asFloat();
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return (int) $this->id;
    }

    /**
     * @return Product
     */
    public function product(): Product
    {
        return $this->productOption->product ?? new Product();
    }
}
