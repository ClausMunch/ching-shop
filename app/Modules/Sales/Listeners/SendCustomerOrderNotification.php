<?php

namespace ChingShop\Modules\Sales\Listeners;

use ChingShop\Modules\Sales\Events\NewOrderEvent;
use ChingShop\Modules\Sales\Notifications\CustomerOrderNotification;
use Illuminate\Contracts\Notifications\Factory;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Dispatch a new order notification for the customer.
 */
class SendCustomerOrderNotification implements ShouldQueue
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
     * @param NewOrderEvent $event
     */
    public function handle(NewOrderEvent $event)
    {
        if (empty($event->order->payerEmail())) {
            return;
        }

        $this->notificationFactory->send(
            collect([$event->order]),
            new CustomerOrderNotification($event->order)
        );
    }
}
