<?php

namespace ChingShop\Modules\Sales\Notifications;

use ChingShop\Modules\Sales\Domain\Order\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

/**
 * Notification for a customer of their new order.
 */
class CustomerOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var Order */
    public $order;

    /**
     * Create a new notification instance.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
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
    public function toMail(Order $order)
    {
        return (new MailMessage())
            ->to($order->customer_email)
            ->view('sales::email.customer-order');
    }
}
