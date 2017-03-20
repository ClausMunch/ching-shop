<?php

namespace ChingShop\Modules\Shipping\Domain;

use ChingShop\Modules\Sales\Domain\Order\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int            $id
 * @property int            $order_id
 * @property \Carbon\Carbon $created_at Dispatch time
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read Order     $order
 */
class Dispatch extends Model
{
    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id', 'order');
    }

    /**
     * @return string
     */
    public function timeTaken(): string
    {
        return $this->created_at->diffForHumans($this->order->created_at, true);
    }
}
