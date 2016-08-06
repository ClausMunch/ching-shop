<?php

namespace Testing\Functional\Customer;

use ChingShop\Actions\MakeUser;
use ChingShop\Modules\User\Model\User;
use Illuminate\Support\Collection;

trait CustomerUsers
{
    /** @var Collection|User[] */
    private $customerUsers;

    /**
     * @return User
     */
    public function customerUser(): User
    {
        if ($this->customerUsers()->isEmpty()) {
            return $this->newCustomerUser();
        }

        return $this->customerUsers()->first();
    }

    /**
     * @return User
     */
    public function newCustomerUser(): User
    {
        $this->customerUsers()->prepend(
            app(MakeUser::class)->make(
                uniqid('customer', false).'@ching-shop.dev', // email
                'customer', // password
                false // is staff?
            )
        );

        return $this->customerUsers()->first();
    }

    /**
     * @return Collection|User[]
     */
    public function customerUsers(): Collection
    {
        if ($this->customerUsers === null) {
            $this->customerUsers = new Collection();
        }

        return $this->customerUsers;
    }
}
