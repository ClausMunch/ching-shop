<?php

namespace Testing\Unit\ChingShop\Action;

use ChingShop\User\UserResource;
use Testing\Unit\UnitTest;

use Mockery\MockInterface;

use ChingShop\Actions\MakeUser;

use ChingShop\User\Role;
use ChingShop\Validation\Validation;
use ChingShop\Validation\ValidationFailure;
use ChingShop\User\RoleResource;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Mockery;

class MakeUserTest extends UnitTest
{
    /** @var MakeUser */
    private $makeUser;

    /** @var Validation|MockInterface */
    private $validation;

    /** @var Hasher|MockInterface */
    private $hasher;

    /** @var RoleResource|MockInterface */
    private $roleResource;

    /**
     * Set up MakeUser action with mock validator
     */
    public function setUp()
    {
        parent::setUp();

        $this->hasher = $this->makeMock(Hasher::class);
        $this->validation = $this->makeMock(Validation::class);
        $this->roleResource = $this->makeMock(RoleResource::class);

        $this->makeUser = new MakeUser($this->validation, $this->hasher, $this->roleResource);
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

        $this->roleResource->shouldNotReceive('mustFindByName');
        $this->roleResource->shouldNotReceive('roles');

        $user = $this->makeUser->make($email, $password, $isStaff);

        $this->assertSame($email, $user->email());
        $this->assertFalse($user->isStaff());
    }

    /**
     * Should be able to make a staff user
     */
    public function testCanMakeStaffUser()
    {
        $email = $this->generator()->anyEmail();
        $password = $this->generator()->anyString();
        $isStaff = true;

        $this->mockPasswordHashing($password);
        $this->mockValidation();

        $this->expectStaffRoleAssociation();

        $this->makeUser->make($email, $password, $isStaff);
    }

    /**
     * User password should be hashed
     */
    public function testHashesPassword()
    {
        $email = $this->generator()->anyEmail();
        $password = $this->generator()->anyString();
        $isStaff = false;

        $mockHash = $this->mockPasswordHashing($password);
        $this->mockValidation();

        $user = $this->makeUser->make($email, $password, $isStaff);

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

        $this->makeUser->make(
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

    /**
     * Expect a user to be added to the staff role
     */
    private function expectStaffRoleAssociation()
    {
        $this->roleResource->shouldReceive('mustFindByName')
            ->with(Role::STAFF)
            ->once()
            ->andReturn($this->roleResource);
        $usersRelationship = $this->makeMock(BelongsToMany::class);
        $this->roleResource->shouldReceive('users')
            ->once()
            ->andReturn($usersRelationship);
        $usersRelationship->shouldReceive('save')
            ->with(Mockery::type(UserResource::class))
            ->once();
    }
}
