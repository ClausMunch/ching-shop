<?php

namespace Testing\Functional\Staff;

use ChingShop\Actions\MakeUser;
use ChingShop\Modules\User\Model\User;

trait StaffUser
{
    /** @var User */
    private $staffUser;

    /** @var MakeUser */
    private $makeUser;

    /**
     * @return User
     */
    private function staffUser()
    {
        if (!isset($this->staffUser)) {
            $this->staffUser = $this->makeStaffUser();
        }

        return $this->staffUser;
    }

    /**
     * @return User
     * @throws \DomainException
     */
    private function makeStaffUser()
    {
        $email = uniqid('staff', false) . '@ching-shop.dev';
        $password = str_random(16);

        return $this->makeUser()->make($email, $password, true);
    }

    /**
     * @return MakeUser
     */
    private function makeUser(): MakeUser
    {
        if (!isset($this->makeUser)) {
            $this->makeUser = app(MakeUser::class);
        }

        return $this->makeUser;
    }
}
