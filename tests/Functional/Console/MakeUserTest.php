<?php

namespace Testing\Functional\Console;

use Testing\Functional\FunctionalTest;

use DB;
use Hash;
use Artisan;

use ChingShop\User\User;
use ChingShop\User\UserResource;

class MakeUserTest extends FunctionalTest
{
    /**
     * Should use email address passed in wih --email option
     */
    public function testMakesWithGivenEmail()
    {
        $email = $this->generator()->anyEmail();

        Artisan::call('make:user', [
            '--email' => $email
        ]);

        $this->seeInDatabase('users', ['email' => $email]);
    }

    /**
     * Should generate a random email if none given
     */
    public function testGeneratesEmailIfNoneGiven()
    {
        $originalUserCount = DB::table('users')->count();
        $originalLatestUserResource = $this->fetchLatestUserResource();

        Artisan::call('make:user');

        $this->assertEquals($originalUserCount + 1, DB::table('users')->count());

        $newLatestUserResource = $this->fetchLatestUserResource();

        $this->assertNotEquals($originalLatestUserResource->id, $newLatestUserResource->id);
        $this->assertStringEndsWith('@ching-shop.com', $newLatestUserResource->email);
    }

    /**
     * Should ask for a password and use it
     */
    public function testGeneratesPassword()
    {
        $password = $this->generator()->anyString();

        Artisan::call('make:user', [
            '--password' => $password
        ]);

        $userResource = $this->fetchLatestUserResource();

        $this->assertTrue(Hash::check($password, $userResource->password));
    }

    /**
     * If the --staff flag is set, should give the new user the Staff role
     */
    public function testMakesStaffUserIfStaffFlagGiven()
    {
        Artisan::call('make:user', [
            '--staff' => true,
        ]);

        $userResource = $this->fetchLatestUserResource();

        $user = new User($userResource);

        $this->assertTrue($user->isStaff());
    }

    /**
     * @return UserResource
     */
    private function fetchLatestUserResource(): UserResource
    {
        $latestUserResource = UserResource::with('roles')
            ->orderBy('updated_at', 'desc')
            ->first();
        return $latestUserResource ? $latestUserResource : new UserResource;
    }
}
