<?php

namespace ChingShop\Modules\Sales\Listeners;

use ChingShop\Modules\Sales\Events\NewOrderEvent;
use ChingShop\Modules\Sales\Notifications\StaffOrderNotification;
use ChingShop\Modules\User\Domain\StaffTelegramGroup;
use ChingShop\Modules\User\Model\Role;
use ChingShop\Modules\User\Model\User;
use Illuminate\Contracts\Notifications\Factory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class SendStaffOrderNotification.
 */
class SendStaffOrderNotifications implements ShouldQueue
{
    /** @var User */
    private $userResource;

    /** @var Factory */
    private $notificationFactory;

    /**
     * @param User    $userResource
     * @param Factory $notificationFactory
     */
    public function __construct(
        User $userResource,
        Factory $notificationFactory
    ) {
        $this->userResource = $userResource;
        $this->notificationFactory = $notificationFactory;
    }

    /**
     * @param NewOrderEvent $event
     */
    public function handle(NewOrderEvent $event)
    {
        $recipients = $this->staffUsers();
        if (\App::environment() !== 'testing') {
            $recipients->add(app(StaffTelegramGroup::class));
        }
        $this->notificationFactory->send(
            $recipients,
            new StaffOrderNotification($event->order)
        );
    }

    /**
     * @return Collection|User[]
     */
    private function staffUsers(): Collection
    {
        return $this->userResource
            ->whereHas(
                'roles',
                function (Builder $roles) {
                    $roles->where('name', '=', Role::STAFF);
                }
            )
            ->get() ?? new Collection();
    }
}
