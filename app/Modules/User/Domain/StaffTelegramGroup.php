<?php

namespace ChingShop\Modules\User\Domain;

use Illuminate\Notifications\Notifiable;
use NotificationChannels\Telegram\TelegramChannel;

/**
 * The staff Telegram channel.
 */
class StaffTelegramGroup
{
    use Notifiable;

    /** @var string */
    public $notifyVia = TelegramChannel::class;

    /** @var string */
    public $email = '';

    /**
     * @return int
     */
    public static function id(): int
    {
        return (int) config('telegram.staff_group');
    }

    /**
     * Route notifications for the Telegram channel.
     *
     * @return int
     */
    public function routeNotificationForTelegram()
    {
        return $this->id();
    }
}
