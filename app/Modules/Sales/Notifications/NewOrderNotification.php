<?php

namespace ChingShop\Modules\Sales\Notifications;

use App;
use ChingShop\Modules\Sales\Domain\Order\Order;
use ChingShop\Modules\User\Model\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

/**
 * Class NewOrder.
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
     * @param Notifiable|User $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        if (isset($notifiable->notifyVia)) {
            return (array) $notifiable->notifyVia;
        }

        return [
            TelegramChannel::class,
            'mail',
        ];
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
        return (new MailMessage())
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
     * @return TelegramMessage
     */
    public function toTelegram()
    {
        $env = App::environment();

        return TelegramMessage::create()
            ->content(
                sprintf(
                    "%s\n%s",
                    "New Order in *{$env}* {$this->order->publicId()}",
                    "Total £{$this->order->totalPrice()}"
                )
            )
            ->button(
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
