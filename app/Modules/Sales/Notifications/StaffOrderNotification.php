<?php

namespace ChingShop\Modules\Sales\Notifications;

use App;
use ChingShop\Modules\Sales\Domain\Offer\OrderOffer;
use ChingShop\Modules\Sales\Domain\Order\Order;
use ChingShop\Modules\Sales\Domain\Order\OrderItem;
use ChingShop\Modules\Sales\Domain\Payment\Settlement;
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
class StaffOrderNotification extends Notification implements ShouldQueue
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
     * @throws \InvalidArgumentException
     *
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage())
            ->to($notifiable->email)
            ->subject(
                sprintf(
                    'New ChingShop order for %s (%s)',
                    $this->order->totalPrice()->formatted(),
                    $this->order->publicId()
                )
            )
            ->line(
                'A new order has been made for'
                ." {$this->order->totalPrice()->formatted()}."
            )
            ->line((string) $this->order->address);

        $this->order->orderItems->each(
            function (OrderItem $item) use ($message) {
                $message->line(
                    "{$item->name()} ({$item->sku()}): {$item->linePrice()}"
                );
            }
        );
        $this->order->orderOffers->each(
            function (OrderOffer $offer) use ($message) {
                $message->line("{$offer->offer_name}: {$offer->linePrice()}");
            }
        );

        $message->action(
            "View new order #{$this->order->publicId()}",
            route('orders.show', [$this->order])
        );

        /** @var Settlement $settlement */
        $settlement = $this->order->payment->settlement;
        $message->line(
            sprintf(
                'View %s payment %s for order #%s: %s',
                $settlement->type(),
                $settlement->id(),
                $this->order->publicId(),
                $settlement->url()
            )
        );

        return $message;
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return TelegramMessage
     */
    public function toTelegram()
    {
        $env = App::environment();

        /** @var Settlement $settlement */
        $settlement = $this->order->payment->settlement;

        return TelegramMessage::create()
            ->content(
                sprintf(
                    "%s\n%s\n%s\n%s",
                    "New Order in *{$env}* {$this->order->publicId()}",
                    "Total {$this->order->totalPrice()->formatted()}",
                    $this->order->orderItems->map(
                        function (OrderItem $item) {
                            return $item->name();
                        }
                    )->implode(', '),
                    $this->order->orderOffers->map(
                        function (OrderOffer $offer) {
                            return $offer->offer_name;
                        }
                    )->implode(', ')
                )
            )
            ->button(
                "View new order #{$this->order->publicId()}",
                route('orders.show', [$this->order])
            )
            ->button(
                sprintf(
                    'View %s payment %s for order #%s',
                    $settlement->type(),
                    $settlement->id(),
                    $this->order->publicId()
                ),
                (string) $settlement->url()
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
