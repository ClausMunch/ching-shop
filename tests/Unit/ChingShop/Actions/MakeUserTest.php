<?php

namespace Testing\Unit\ChingShop\Action;

use Testing\Unit\UnitTest;

use Mockery\MockInterface;
use ChingShop\Actions\MakeUser;

use ChingShop\Validation\Validation;
use ChingShop\Validation\ValidationFailure;
use Illuminate\Contracts\Hashing\Hasher;

use Mockery;

class MakeUserTest extends UnitTest
{
    /** @var MakeUser */
    private $userProvider;

    /** @var Validation|MockInterface */
    private $validation;

    /** @var Hasher|MockInterface */
    private $hasher;

    /**
     * Set up MakeUser action with mock validator
     */
    public function setUp()
    {
        parent::setUp();

        $this->hasher = $this->makeMock(Hasher::class);
        $this->validation = $this->makeMock(Validation::class);

        $this->userProvider = new MakeUser($this->validation, $this->hasher);
    }

    /**
     * Should be able to make non-staff user
     */
    public function testCanMakeNonStaffUser()
    {
        $email = $this->generator()->anyEmail();
        $password = $this->generator()->anyString();
        $isStaff = false;

        $this->mockPasswordHashing($password);
        $this->mockValidation();

        $user = $this->userProvider->make($email, $password, $isStaff);

        $this->assertSame($email, $user->email());
        $this->assertFalse($user->isStaff());
    }

    /**
     * User password should be hashed
     */
    public function testHashesPassword()
    {
        $email = $this->generator()->anyEmail();
        $password = $this->generator()->anyString();
        $isStaff = $this->generator()->anyBoolean();

        $mockHash = $this->mockPasswordHashing($password);
        $this->mockValidation();

        $user = $this->userProvider->make($email, $password, $isStaff);

        $this->assertSame($mockHash, $user->hashedPassword());
    }

    /**
     * Should reject if validator fails to validate
     */
    public function testRejectsInvalidParams()
    {
        $this->setExpectedExceptionRegExp(
            ValidationFailure::class
        );

        $password = $this->generator()->anyString();
        $this->mockPasswordHashing($password);

        $email = $this->generator()->anyEmail();

        $this->mockValidation($email, $password, false);

        $this->userProvider->make(
            $email,
            $password,
            $this->generator()->anyBoolean()
        );
    }

    /**
     * @param $password
     * @return string|\Testing\Generator\string
     */
    private function mockPasswordHashing($password)
    {
        $mockHash = $this->generator()->anyString();
        $this->hasher->shouldReceive('make')->with($password)->andReturn($mockHash);
        return $mockHash;
    }

    /**
     * @param string $email
     * @param string $password
     * @param bool $pass
     */
    private function mockValidation(
        string $email    = '',
        string $password = '',
        bool   $pass     = true
    ) {
        $this->validation->shouldReceive('passes')->zeroOrMoreTimes()->with(
            $email && $password ? compact('email', 'password') : Mockery::any(),
            Mockery::any()
        )->andReturn($pass);
    }
}
