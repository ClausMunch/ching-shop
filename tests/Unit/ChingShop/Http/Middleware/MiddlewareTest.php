<?php

namespace Testing\Unit\ChingShop\Http\Middleware;

use Testing\Unit\UnitTest;
use Mockery\MockInterface;
use Illuminate\Http\Request;
use ChingShop\User\User;

abstract class MiddlewareTest extends UnitTest
{
    /** @var Request|MockInterface */
    protected $request;

    /**
     * Initialise Authenticate middleware for each test
     */
    public function setUp()
    {
        parent::setUp();
        $this->request = $this->makeMock(Request::class);
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
        $user = $this->makeMock(User::class);
        $this->request
            ->shouldReceive('user')
            ->zeroOrMoreTimes()
            ->andReturn($user);
        return $user;
    }
}
