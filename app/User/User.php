<?php

namespace ChingShop\User;

class User
{
    /** @var UserResource */
    private $userResource;

    /**
     * @param UserResource $userResource
     */
    public function __construct(UserResource $userResource)
    {
        $this->userResource = $userResource;
    }

    /**
     * @return string
     */
    public function email()
    {
        return $this->userResource->email;
    }

    /**
     * @return bool
     */
    public function isStaff(): bool
    {
        return $this->userResource->roles
            && $this->userResource->roles->contains('name', Role::STAFF);
    }

    /**
     * @return string
     */
    public function hashedPassword(): string
    {
        return $this->userResource->password;
    }
}
