<?php

namespace ChingShop\Modules\Sales\Model;

use ChingShop\Modules\Catalogue\Model\Inventory\StockItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin \Eloquent
 *
 * @property int            $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read Order     $order
 */
class OrderItem extends Model
{
    use SoftDeletes;

    /**
     * An order item is part of an order.
     *
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * An order item has an allocated stock item.
     *
     * @return HasOne
     */
    public function stockItem(): HasOne
    {
        return $this->hasOne(StockItem::class);
    }
}