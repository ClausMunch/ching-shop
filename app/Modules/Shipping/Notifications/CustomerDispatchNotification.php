<?php

namespace ChingShop\Modules\Shipping\Notifications;

use ChingShop\Modules\Sales\Domain\Order\Order;
use ChingShop\Modules\Shipping\Domain\Dispatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Log;

/**
 * Notification for a customer about their order being dispatched.
 */
class CustomerDispatchNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var Dispatch */
    public $dispatch;

    /**
     * @param Dispatch $dispatch
     */
    public function __construct(Dispatch $dispatch)
    {
        $this->dispatch = $dispatch;
    }

    /**
     * @return array
     */
    public function via()
    {
        return ['mail'];
    }

    /**
     * @param Order|Notifiable $order
     *
     * @return MailMessage
     */
    public function toMail(Order $order): MailMessage
    {
        Log::debug(
            "Building customer email about dispatch of order #{$order->id}."
        );

        return (new MailMessage())
            ->subject(
                "Dispatch update for Ching-Shop.com Order #{$order->publicId()}"
            )
            ->view('sales::email.customer-dispatch', ['order' => $order]);
    }
}
