<?php

namespace ChingShop\Modules\Sales\Events;

use ChingShop\Events\Event;
use ChingShop\Modules\Sales\Domain\Order\Order;
use Illuminate\Queue\SerializesModels;

/**
 * Class NewOrderEvent.
 */
class NewOrderEvent extends Event
{
    use SerializesModels;

    /** @var Order */
    public $order;

    /**
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
