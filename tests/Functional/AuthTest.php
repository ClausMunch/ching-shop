<?php

namespace Testing\Functional;

use ChingShop\Actions\MakeUser;

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
        $user = $this->makeUser()->make($email, $password, true);

        $this->visit(route('auth.login'))
            ->type($email, 'email')
            ->type($password, 'password')
            ->press('Log in')
            ->seePageIs(route('staff.dashboard'));

        $user->delete();
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
