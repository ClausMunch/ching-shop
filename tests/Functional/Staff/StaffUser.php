<?php

namespace Testing\Functional\Staff;

use ChingShop\Actions\MakeUser;
use ChingShop\User\User;

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
     */
    private function makeStaffUser()
    {
        $email = str_random().'@ching-shop.com';
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
