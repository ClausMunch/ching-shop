<?php

namespace Testing\Unit\ChingShop\Http\Middleware;

use ChingShop\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class RedirectIfAuthenticatedTest extends MiddlewareTest
{
    /** @var RedirectIfAuthenticated */
    private $redirectIfAuthenticated;

    /** @var Guard|MockObject */
    private $guard;

    /**
     * Set up redirect middleware with mock dependencies.
     */
    public function setUp()
    {
        parent::setUp();

        $this->guard = $this->makeMock(Guard::class);

        $this->redirectIfAuthenticated = new RedirectIfAuthenticated(
            $this->guard
        );
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(
            RedirectIfAuthenticated::class,
            $this->redirectIfAuthenticated
        );
    }

    /**
     * Should redirect to /home if authenticated.
     */
    public function testRedirectsToHomeIfAuthenticated()
    {
        $this->guard->expects($this->atLeastOnce())
            ->method('check')
            ->willReturn(true);

        $next = function () {
        };

        $response = $this->redirectIfAuthenticated->handle(
            $this->request,
            $next
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    /**
     * Should pass the request on if not authenticated.
     */
    public function testPassesOnIfNotAuthenticated()
    {
        $passedOn = false;
        $next = function (Request $passedRequest) use (&$passedOn) {
            $this->assertSame($passedRequest, $this->request);
            $passedOn = true;
        };

        $this->redirectIfAuthenticated->handle($this->request, $next);

        $this->assertTrue($passedOn);
    }
}
