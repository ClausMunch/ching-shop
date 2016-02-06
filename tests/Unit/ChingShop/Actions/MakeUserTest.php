<?php

namespace Testing\Unit\ChingShop\Action;

use ChingShop\User\User;
use Testing\Unit\UnitTest;

use Mockery;
use Mockery\MockInterface;

use PHPUnit_Framework_MockObject_MockObject as MockObject;

use ChingShop\Actions\MakeUser;

use ChingShop\User\Role;
use ChingShop\Validation\ValidationInterface;
use ChingShop\Validation\ValidationFailure;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MakeUserTest extends UnitTest
{
    /** @var MakeUser */
    private $makeUser;

    /** @var ValidationInterface|MockObject */
    private $validation;

    /** @var Hasher|MockInterface */
    private $hasher;

    /** @var Role|MockObject */
    private $role;

    /**
     * Set up MakeUser action with mock validator
     */
    public function setUp()
    {
        parent::setUp();

        $this->hasher = $this->mockery(Hasher::class);
        $this->validation = $this->makeMock(ValidationInterface::class);
        $this->role = $this->makeMock(Role::class);

        $this->makeUser = new MakeUser(
            $this->validation,
            $this->hasher,
            $this->role
        );
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
        $this->hasher->shouldReceive('make')
            ->with($password)
            ->andReturn($mockHash);
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
        $this->validation->expects($this->atLeastOnce())
            ->method('passes')
            ->with(
                $email && $password ?
                    compact('email', 'password') : $this->anything(),
                $this->anything()
            )
            ->willReturn($pass);
    }

    /**
     * Expect a user to be added to the staff role
     */
    private function expectStaffRoleAssociation()
    {
        $this->role->expects($this->once())
            ->method('mustFindByName')
            ->with(Role::STAFF)
            ->willReturn($this->role);
        $usersRelationship = $this->makeMock(BelongsToMany::class);
        $this->role->expects($this->once())
            ->method('users')
            ->willReturn($usersRelationship);
        $usersRelationship->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(User::class));
    }
}
