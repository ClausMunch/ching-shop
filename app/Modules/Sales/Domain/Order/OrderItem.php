<?php

namespace ChingShop\Modules\Sales\Domain\Order;

use ChingShop\Modules\Catalogue\Domain\Category;
use ChingShop\Modules\Catalogue\Domain\Inventory\StockItem;
use ChingShop\Modules\Sales\Domain\Basket\BasketItem;
use ChingShop\Modules\Sales\Domain\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin \Eloquent
 *
 * @property int                 $id
 * @property float               $price
 * @property \Carbon\Carbon      $created_at
 * @property \Carbon\Carbon      $updated_at
 * @property \Carbon\Carbon      $deleted_at
 * @property-read Order          $order
 * @property-read BasketItem     $basketItem
 * @property-read StockItem|null $stockItem
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
     * @return Money
     * @throws \InvalidArgumentException
     */
    public function price(): Money
    {
        return Money::fromDecimal($this->price);
    }

    /**
     * @return float
     * @throws \InvalidArgumentException
     */
    public function priceAsFloat(): float
    {
        return $this->price()->asFloat();
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->basketItem->productOption->name();
    }

    /**
     * @return string
     */
    public function sku()
    {
        return $this->basketItem->productOption->supplier_number;
    }

    /**
     * @return \ChingShop\Modules\Catalogue\Domain\Category
     */
    public function category()
    {
        return $this->basketItem
            ->productOption
            ->product
            ->category ?? new Category();
    }

    /**
     * @throws \Exception
     *
     * @return bool|null
     */
    public function deAllocate()
    {
        if ($this->stockItem) {
            $this->stockItem->orderItem()->dissociate()->save();
        }

        return $this->delete();
    }
}
