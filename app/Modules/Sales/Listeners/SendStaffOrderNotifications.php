<?php

namespace ChingShop\Modules\Sales\Listeners;

use ChingShop\Modules\Sales\Domain\Address;
use ChingShop\Modules\Sales\Events\NewOrderEvent;
use ChingShop\Modules\Sales\Jobs\PrintOrderAddress;
use ChingShop\Modules\Sales\Notifications\StaffOrderNotification;
use ChingShop\Modules\User\Domain\StaffTelegramGroup;
use ChingShop\Modules\User\Model\Role;
use ChingShop\Modules\User\Model\User;
use Illuminate\Contracts\Notifications\Factory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Log;
use Queue;
use Throwable;

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
        try {
            $this->dispatchPrintAddressJob($event->order->address);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
        }
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

    /**
     * @param Address $address
     */
    private function dispatchPrintAddressJob(Address $address)
    {
        $job = [
            'queued_at' => date(DATE_W3C),
            'order_id'  => $address->order->publicId(),
            'address'   => $address->toArray(),
            'attempts'  => 0, // To keep Laravel happy.
        ];

        Queue::connection(PrintOrderAddress::QUEUE_CONNECTION)->pushRaw(
            json_encode($job),
            PrintOrderAddress::QUEUE_NAME
        );
    }
}
