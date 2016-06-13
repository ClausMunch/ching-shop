<?php

namespace Testing\Unit\ChingShop\Http\Middleware;

use ChingShop\Http\Middleware\Authenticate;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateTest extends MiddlewareTest
{
    /** @var Authenticate */
    private $authenticate;

    /** @var Guard|MockInterface */
    private $guard;

    /**
     * Initialise Authenticate middleware for each test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->guard = $this->mockery(Guard::class);
        $this->authenticate = new Authenticate($this->guard);
    }

    /**
     * Sanity check that authenticate middleware can be instantiated.
     */
    public function testCanInstantiate()
    {
        $this->assertInstanceOf(Authenticate::class, $this->authenticate);
    }

    /**
     * Should pass an authenticated request to the next middleware.
     */
    public function testPassesOnAuthenticatedResponse()
    {
        $passedOn = false;
        $next = function (Request $passedRequest) use (&$passedOn) {
            $this->assertSame($passedRequest, $this->request);
            $passedOn = true;
        };

        $this->requestIsAuthenticated(true);
        $this->requestIsAjax($this->generator()->anyBoolean());

        $this->authenticate->handle($this->request, $next);

        $this->assertTrue($passedOn);
    }

    /**
     * Should give a 401 for an unauthenticated AJAX request.
     */
    public function testGives401ForUnauthenticatedAjax()
    {
        $next = function () {
        };

        $this->requestIsAuthenticated(false);
        $this->requestIsAjax(true);

        /** @var Response $response */
        $response = $this->authenticate->handle($this->request, $next);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    /**
     * Should redirect to login page for unauthenticated general request.
     */
    public function testRedirectsToLoginForUnauthenticatedRequest()
    {
        $next = function () {
        };

        $this->requestIsAuthenticated(false);
        $this->requestIsAjax(false);

        /** @var RedirectResponse $redirect */
        $redirect = $this->authenticate->handle($this->request, $next);

        $this->assertInstanceOf(RedirectResponse::class, $redirect);
        $this->assertSame(Response::HTTP_FOUND, $redirect->getStatusCode());
        $this->assertSame(route('auth::login'), $redirect->getTargetUrl());
    }

    /**
     * @param bool $isAuthenticated
     */
    private function requestIsAuthenticated(bool $isAuthenticated)
    {
        $this->guard->shouldReceive('guest')
            ->zeroOrMoreTimes()
            ->andReturn(!$isAuthenticated);
    }
}
