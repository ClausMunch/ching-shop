<?php

namespace ChingShop\User;

class Role
{
    const STAFF = 'staff';

    /** @var RoleResource */
    private $roleResource;

    /**
     * @param RoleResource $roleResource
     */
    public function __construct(RoleResource $roleResource)
    {
        $this->roleResource = $roleResource;
    }
}
