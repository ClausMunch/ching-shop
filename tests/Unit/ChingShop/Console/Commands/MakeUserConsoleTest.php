<?php

namespace Testing\Unit\ChingShop\Console\Commands;

use ChingShop\Console\Commands\MakeUser;
use ChingShop\Modules\User\Model\Role;
use ChingShop\Modules\User\Model\User;
use Illuminate\Contracts\Hashing\Hasher;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class MakeUserTest.
 */
class MakeUserConsoleTest extends CommandTest
{
    /** @var MakeUser */
    private $makeUser;

    /** @var Hasher|MockObject */
    private $hasher;

    /** @var User|MockObject */
    private $userResource;

    /** @var Role|MockObject */
    private $roleResource;

    /**
     * Create MakeUser instance for each test.
     */
    public function setUp()
    {
        parent::setUp();

        $this->makeUser = new MakeUser();
        $this->makeUser->setLaravel($this->container);

        $this->hasher = $this->makeMock(Hasher::class);
        $this->container->bind('hash', function () {
            return $this->hasher;
        });

        $this->userResource = $this->makeMock(User::class);
        $this->container->bind(User::class, function () {
            return $this->userResource;
        });

        $this->roleResource = $this->makeMock(Role::class);
        $this->container->bind(Role::class, function () {
            return $this->roleResource;
        });
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(MakeUser::class, $this->makeUser);
    }

    /**
     * Should make a user with the given email.
     */
    public function testUsesGivenEmail()
    {
        $email = $this->generator()->anyEmail();

        $this->userResource->expects($this->atLeastOnce())
            ->method('setAttribute')
            ->withConsecutive(
                ['email', $email],
                $this->anything() // password
            );

        $tester = $this->commandTester($this->makeUser);
        $tester->execute([
            '--email' => $email,
        ]);
    }

    /**
     * Should generate an email address if none given.
     */
    public function testGeneratesEmailAddressIfNoneGiven()
    {
        $this->userResource->expects($this->atLeastOnce())
            ->method('setAttribute')
            ->withConsecutive(
                [
                    'email',
                    $this->matchesRegularExpression(
                        '/[a-zA-Z0-9]+@ching-shop.com/'
                    ),
                ],
                $this->anything() // password
            );

        $tester = $this->commandTester($this->makeUser);
        $tester->execute([]);
    }

    /**
     * Should make a user with the given password.
     */
    public function testUsesGivenPassword()
    {
        $password = $this->generator()->anyString();
        $hash = $this->generator()->anyString();
        $this->hasher->expects($this->atLeastOnce())
            ->method('make')
            ->with($password)
            ->willReturn($hash);

        $this->userResource->expects($this->atLeastOnce())
            ->method('setAttribute')
            ->withConsecutive(
                $this->anything(), // email
                ['password', $hash]
            );

        $tester = $this->commandTester($this->makeUser);
        $tester->execute([
            '--password' => $password,
        ]);
    }

    /**
     * Should generate a password if none given.
     */
    public function testGeneratesPasswordIfNoneGiven()
    {
        $this->hasher->expects($this->atLeastOnce())
            ->method('make')
            ->with($this->isType('string'))
            ->willReturn($this->generator()->anyString());

        $this->userResource->expects($this->atLeastOnce())
            ->method('setAttribute')
            ->withConsecutive(
                $this->anything(), // email
                ['password', $this->isType('string')]
            );

        $tester = $this->commandTester($this->makeUser);
        $tester->execute([]);
    }

    /**
     * Should create a staff user if the --staff option is given.
     */
    public function testCreatesStaffUserWithFlag()
    {
        $this->roleResource->expects($this->atLeastOnce())
            ->method('mustFindByName')
            ->with(Role::STAFF)
            ->willReturn($this->roleResource);

        $this->userResource->expects($this->atLeastOnce())
            ->method('roles');

        $tester = $this->commandTester($this->makeUser);
        $tester->execute([
            '--staff' => true,
        ]);
    }

    /**
     * Should create a non-staff user if the --staff option is not given.
     */
    public function testDoesNotCreateStaffUserWithoutFlag()
    {
        $this->roleResource->expects($this->never())
            ->method('mustFindByName');

        $this->userResource->expects($this->never())
            ->method('roles');

        $tester = $this->commandTester($this->makeUser);
        $tester->execute([]);
    }
}
