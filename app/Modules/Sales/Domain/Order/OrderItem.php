<?php

namespace ChingShop\Modules\Sales\Domain\Order;

use ChingShop\Modules\Catalogue\Domain\Inventory\StockItem;
use ChingShop\Modules\Sales\Domain\Basket\BasketItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin \Eloquent
 *
 * @property int             $id
 * @property float          $price
 * @property \Carbon\Carbon  $created_at
 * @property \Carbon\Carbon  $updated_at
 * @property \Carbon\Carbon  $deleted_at
 * @property-read Order      $order
 * @property-read BasketItem $basketItem
 */
class OrderItem extends Model
{
    use SoftDeletes;

    /** @var array */
    protected $casts = [
        'price' => 'double',
    ];

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

    /**
     * @return BelongsTo
     */
    public function basketItem(): BelongsTo
    {
        return $this->belongsTo(BasketItem::class);
    }

    /**
     * @return float
     */
    public function priceAsFloat(): float
    {
        return (float) $this->price;
    }
}
