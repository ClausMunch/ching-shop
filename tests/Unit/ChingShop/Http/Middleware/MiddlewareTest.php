<?php

namespace Testing\Unit\ChingShop\Http\Middleware;

use ChingShop\User\User;
use Illuminate\Http\Request;
use Mockery\MockInterface;
use Testing\Unit\UnitTest;

abstract class MiddlewareTest extends UnitTest
{
    /** @var Request|MockInterface */
    protected $request;

    /**
     * Initialise Authenticate middleware for each test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->request = $this->mockery(Request::class);
    }

    /**
     * @param bool $isAjax
     */
    protected function requestIsAjax(bool $isAjax = true)
    {
        $this->request->shouldReceive('ajax')
            ->zeroOrMoreTimes()
            ->andReturn($isAjax);
    }

    /**
     * @return MockInterface|User
     */
    protected function mockRequestUser(): MockInterface
    {
        $user = $this->mockery(User::class);
        $this->request
            ->shouldReceive('user')
            ->zeroOrMoreTimes()
            ->andReturn($user);

        return $user;
    }
}
