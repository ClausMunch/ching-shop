<?php

namespace Testing\Functional\Staff;

use Testing\Functional\FunctionalTest;
use ChingShop\User\User;

class DashboardTest extends FunctionalTest
{
    /** @var User */
    private $user;

    /**
     * Set up user for dashboard testing
     */
    public function setUp()
    {
        parent::setUp();

        $email = str_random() . '@ching-shop.com';
        $password = str_random(16);
        $this->user = factory(\ChingShop\User\User::class)->create([
            'email'    => $email,
            'password' => bcrypt($password),
        ]);
    }

    /**
     * Should not be able to access dashboard pages without auth
     */
    public function testAuthRequired()
    {
        $this->visit(route('staff.dashboard'))
            ->seePageIs(route('auth.login'));
    }

    /**
     * Should be able to hit the index page
     */
    public function testIndex()
    {
        $this->actingAs($this->user)
            ->visit(route('staff.dashboard'))
            ->seePageIs(route('staff.dashboard'));
    }
}
