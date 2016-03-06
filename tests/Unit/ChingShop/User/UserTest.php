<?php

namespace Testing\Unit\ChingShop\User;

use ChingShop\User\User;
use Testing\Unit\UnitTest;

class UserTest extends UnitTest
{
    /** @var User */
    private $user;

    /**
     * Set up user for each test.
     */
    public function setUp()
    {
        $this->user = new User();
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(User::class, $this->user);
    }

    /**
     * Sanity check email.
     */
    public function testEmail()
    {
        $this->user->email = $this->generator()->anyEmail();
        $this->assertSame($this->user->email, $this->user->email());
    }

    /**
     * Should use roles to determine if user is staff.
     */
    public function testIsStaff()
    {
        $this->user->setAttribute('roles', []);
        $this->assertFalse($this->user->isStaff());
    }
}
