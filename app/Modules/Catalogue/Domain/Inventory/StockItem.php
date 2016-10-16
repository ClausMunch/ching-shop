<?php

namespace ChingShop\Modules\Catalogue\Domain\Inventory;

use ChingShop\Modules\Catalogue\Domain\Product\ProductOption;
use ChingShop\Modules\Sales\Domain\Order\OrderItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * An individual, real-world item of stock for a specific product option.
 *
 * @mixin \Eloquent
 *
 * @property int            $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property ProductOption  $productOption
 * @property OrderItem      $orderItem
 *
 * @method Builder available()
 */
class StockItem extends Model
{
    use SoftDeletes;

    /**
     * A stock item is a physical instance of a product option.
     *
     * @return BelongsTo
     */
    public function productOption(): BelongsTo
    {
        return $this->belongsTo(ProductOption::class);
    }

    /**
     * A stock item can be assigned to an order item.
     *
     * @return BelongsTo
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * @return bool
     */
    public function isAvailable()
    {
        return $this->id xor $this->orderItem;
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function scopeAvailable(Builder $builder)
    {
        return $builder->doesntHave('orderItem');
    }
}
