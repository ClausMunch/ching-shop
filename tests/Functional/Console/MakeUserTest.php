<?php

namespace Testing\Functional\Console;

use Artisan;
use ChingShop\Modules\User\Model\User;
use DB;
use Hash;
use Testing\Functional\FunctionalTest;

class MakeUserTest extends FunctionalTest
{
    /**
     * Should use email address passed in wih --email option.
     */
    public function testMakesWithGivenEmail()
    {
        $email = $this->generator()->anyEmail();

        Artisan::call('make:user', [
            '--email' => $email,
        ]);

        $this->seeInDatabase('users', ['email' => $email]);
    }

    /**
     * Should generate a random email if none given.
     */
    public function testGeneratesEmailIfNoneGiven()
    {
        $originalUserCount = DB::table('users')->count();
        $originalLatestUserResource = $this->fetchLatestUser();

        Artisan::call('make:user');

        $this->assertEquals($originalUserCount + 1, DB::table('users')->count());

        $newLatestUserResource = $this->fetchLatestUser();

        $this->assertNotEquals($originalLatestUserResource->id, $newLatestUserResource->id);
        $this->assertStringEndsWith('@ching-shop.com', $newLatestUserResource->email);
    }

    /**
     * Should ask for a password and use it.
     */
    public function testGeneratesPassword()
    {
        $password = $this->generator()->anyString();

        Artisan::call('make:user', [
            '--password' => $password,
        ]);

        $userResource = $this->fetchLatestUser();

        $this->assertTrue(Hash::check($password, $userResource->password));
    }

    /**
     * If the --staff flag is set, should give the new user the Staff role.
     */
    public function testMakesStaffUserIfStaffFlagGiven()
    {
        Artisan::call('make:user', [
            '--staff' => true,
        ]);

        $user = $this->fetchLatestUser();

        $this->assertTrue($user->isStaff());
    }

    /**
     * @return User
     */
    private function fetchLatestUser(): User
    {
        $latestUserResource = User::with('roles')
            ->orderBy('updated_at', 'desc')
            ->first();

        return $latestUserResource ? $latestUserResource : new User();
    }
}
