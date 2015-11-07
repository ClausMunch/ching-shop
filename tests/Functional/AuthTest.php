<?php

namespace Testing\Functional;

class AuthTest extends FunctionalTest
{
    /**
     * Should be able to visit login page
     */
    public function testGetLogin()
    {
        $this->visit(route('auth.login'))
            ->see('Login');
    }

    /**
     * Should give feedback for empty login attempt
     */
    public function testEmptyLoginFeedback()
    {
        $this->visit(route('auth.login'))
            ->type('', 'email')
            ->type('', 'password')
            ->press('Log in')
            ->see('The email field is required')
            ->see('The password field is required');
    }

    /**
     * Successful login should go to staff dashboard
     */
    public function testCorrectLogin()
    {
        $email = str_random() . '@ching-shop.com';
        $password = str_random(16);
        $user = factory(\ChingShop\User\UserResource::class)->create([
            'email'    => $email,
            'password' => bcrypt($password),
        ]);

        $this->visit(route('auth.login'))
            ->type($email, 'email')
            ->type($password, 'password')
            ->press('Log in')
            ->seePageIs(route('staff.dashboard'));

        $user->delete();
    }
}
