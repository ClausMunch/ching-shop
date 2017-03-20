<?php

namespace ChingShop\Modules\Shipping\Listeners;

use ChingShop\Modules\Shipping\Events\OrderDispatchedEvent;
use ChingShop\Modules\Shipping\Notifications\CustomerDispatchNotification;
use Illuminate\Contracts\Notifications\Factory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

/**
 * Notify the customer that their order has been dispatched.
 */
class SendCustomerDispatchNotification implements ShouldQueue
{
    /** @var Factory */
    private $notificationFactory;

    /**
     * @param Factory $notificationFactory
     */
    public function __construct(Factory $notificationFactory)
    {
        $this->notificationFactory = $notificationFactory;
    }

    /**
     * @param OrderDispatchedEvent $event
     */
    public function handle(OrderDispatchedEvent $event)
    {
        if (empty($event->dispatch->order)) {
            Log::notice('No order for dispatch event; skipping.');

            return;
        }

        Log::debug(
            sprintf(
                'Sending dispatch email for order #%s',
                $event->dispatch->order->publicId()
            )
        );

        $this->notificationFactory->send(
            collect([$event->dispatch->order]),
            new CustomerDispatchNotification($event->dispatch)
        );
    }
}
