<?php

namespace ChingShop\Providers;

use ChingShop\Events\NewImageEvent;
use ChingShop\Listeners\NewImageListener;
use ChingShop\Modules\Sales\Events\NewOrderEvent;
use ChingShop\Modules\Sales\Events\NewPayPalSettlementEvent;
use ChingShop\Modules\Sales\Listeners\SendCustomerOrderNotification;
use ChingShop\Modules\Sales\Listeners\SendStaffOrderNotifications;
use ChingShop\Modules\Sales\Listeners\SetPayPalTransactionId;
use ChingShop\Modules\Shipping\Events\OrderDispatchedEvent;
use ChingShop\Modules\Shipping\Listeners\SendCustomerDispatchNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;

/**
 * Class EventServiceProvider.
 */
class EventServiceProvider extends Provider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        NewImageEvent::class            => [
            NewImageListener::class,
        ],
        NewOrderEvent::class            => [
            SendStaffOrderNotifications::class,
            SendCustomerOrderNotification::class,
        ],
        NewPayPalSettlementEvent::class => [
            SetPayPalTransactionId::class,
        ],
        OrderDispatchedEvent::class     => [
            SendCustomerDispatchNotification::class,
        ],
    ];
}
