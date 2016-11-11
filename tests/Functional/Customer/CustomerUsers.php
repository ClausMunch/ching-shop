<?php

namespace Testing\Functional\Customer;

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
            factory(User::class)->create()
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
