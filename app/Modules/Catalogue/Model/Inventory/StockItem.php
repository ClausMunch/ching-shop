<?php

namespace ChingShop\Modules\Catalogue\Model\Inventory;

use ChingShop\Modules\Catalogue\Model\Product\ProductOption;
use ChingShop\Modules\Sales\Model\OrderItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Eloquent
 *
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property ProductOption|Collection $productOption
 * @property OrderItem|Collection $orderItem
 */
class StockItem extends Model
{
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
}
