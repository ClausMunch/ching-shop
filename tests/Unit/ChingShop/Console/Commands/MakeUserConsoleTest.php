<?php

namespace Testing\Unit\ChingShop\Console\Commands;

use ChingShop\Console\Commands\MakeUser;
use ChingShop\User\Role;
use ChingShop\User\User;
use Illuminate\Container\Container;
use Illuminate\Contracts\Hashing\Hasher;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Symfony\Component\Console\Tester\CommandTester;
use Testing\Unit\UnitTest;

/**
 * Class MakeUserTest.
 */
class MakeUserConsoleTest extends UnitTest
{
    /** @var MakeUser */
    private $makeUser;

    /** @var Container */
    private $container;

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
        $this->makeUser = new MakeUser();
        $this->container = new Container();
        Container::setInstance($this->container);
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

        $tester = new CommandTester($this->makeUser);
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

        $tester = new CommandTester($this->makeUser);
        $tester->execute([]);
    }

    /**
     * Should make a user with the given password.
     */
    public function testUsesGivenPassword()
    {
        $password = $this->generator()->anyString();
        $hash = $this->generator()->anyString();
        $this->hasher->expects($this->once())
            ->method('make')
            ->with($password)
            ->willReturn($hash);

        $this->userResource->expects($this->atLeastOnce())
            ->method('setAttribute')
            ->withConsecutive(
                $this->anything(), // email
                ['password', $hash]
            );

        $tester = new CommandTester($this->makeUser);
        $tester->execute([
            '--password' => $password,
        ]);
    }

    /**
     * Should generate a password if none given.
     */
    public function testGeneratesPasswordIfNoneGiven()
    {
        $this->hasher->expects($this->once())
            ->method('make')
            ->with($this->isType('string'))
            ->willReturn($this->generator()->anyString());

        $this->userResource->expects($this->atLeastOnce())
            ->method('setAttribute')
            ->withConsecutive(
                $this->anything(), // email
                ['password', $this->isType('string')]
            );

        $tester = new CommandTester($this->makeUser);
        $tester->execute([]);
    }

    /**
     * Should create a staff user if the --staff option is given.
     */
    public function testCreatesStaffUserWithFlag()
    {
        $this->roleResource->expects($this->once())
            ->method('mustFindByName')
            ->with(Role::STAFF)
            ->willReturn($this->roleResource);

        $this->userResource->expects($this->once())
            ->method('roles');

        $tester = new CommandTester($this->makeUser);
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

        $tester = new CommandTester($this->makeUser);
        $tester->execute([]);
    }
}
