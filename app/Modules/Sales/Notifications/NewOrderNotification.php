<?php

namespace ChingShop\Modules\Sales\Notifications;

use ChingShop\Modules\Sales\Domain\Order\Order;
use ChingShop\Modules\User\Model\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class NewOrder
 * @package ChingShop\Modules\Sales\Notifications
 */
class NewOrderNotification extends Notification implements ShouldQueue
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
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via()
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param Notifiable|User $notifiable
     *
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->to($notifiable->email)
            ->subject(
                sprintf(
                    'New ChingShop order for £%s (%s)',
                    $this->order->totalPrice(),
                    $this->order->publicId()
                )
            )
            ->line(
                "Hi {$notifiable->name},"
            )
            ->line(
                "A new order has been made for £{$this->order->totalPrice()}."
            )
            ->action(
                "View new order #{$this->order->publicId()}",
                route('orders.show', [$this->order])
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray()
    {
        return [];
    }
}
