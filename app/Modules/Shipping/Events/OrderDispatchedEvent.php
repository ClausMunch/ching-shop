<?php

namespace ChingShop\Modules\Shipping\Events;

use ChingShop\Events\Event;
use ChingShop\Modules\Shipping\Domain\Dispatch;
use Illuminate\Queue\SerializesModels;

/**
 * Class OrderDispatchedEvent.
 */
class OrderDispatchedEvent extends Event
{
    use SerializesModels;

    /** @var Dispatch */
    public $dispatch;

    /**
     * @param Dispatch $dispatch
     */
    public function __construct(Dispatch $dispatch)
    {
        $this->dispatch = $dispatch;
    }
}
