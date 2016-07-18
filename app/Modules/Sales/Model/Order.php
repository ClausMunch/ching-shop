<?php

namespace ChingShop\Modules\Sales\Model;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin \Eloquent
 *
 * @property int                    $id
 * @property \Carbon\Carbon         $created_at
 * @property \Carbon\Carbon         $updated_at
 * @property string                 $deleted_at
 *
 * @property OrderItem[]|Collection $orderItems
 */
class Order extends Model
{
    /**
     * An order contains order items.
     *
     * @return HasMany
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
