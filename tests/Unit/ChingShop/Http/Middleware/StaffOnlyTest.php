<?php

namespace Testing\Unit\ChingShop\Http\Middleware;

use ChingShop\Http\Middleware\StaffOnly;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffOnlyTest extends MiddlewareTest
{
    /** @var StaffOnly */
    private $staffOnly;

    /**
     * Initialise staff only middleware for each test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->staffOnly = new StaffOnly();
        $this->request = $this->mockery(Request::class);
    }

    /**
     * Sanity check that staff only middleware can be initialised.
     */
    public function testCanInitialise()
    {
        $this->assertInstanceOf(StaffOnly::class, $this->staffOnly);
    }

    /**
     * Should pass on a request from a staff user.
     */
    public function testPassesOnRequestFromStaffUser()
    {
        $passedOn = false;
        $next = function (Request $passedRequest) use (&$passedOn) {
            $this->assertSame($passedRequest, $this->request);
            $passedOn = true;
        };

        $this->requestIsAjax($this->generator()->anyBoolean());

        $user = $this->mockRequestUser();
        $user->shouldReceive('isStaff')->andReturn(true);

        $this->staffOnly->handle($this->request, $next);

        $this->assertTrue($passedOn);
    }

    /**
     * Should redirect a request from a non-staff user to the login page.
     */
    public function testRedirectsNonAjaxNonStaffUserRequestToLogin()
    {
        $next = function () {};

        $user = $this->mockRequestUser();
        $user->shouldReceive('isStaff')->andReturn(false);

        $this->requestIsAjax(false);

        /** @var RedirectResponse $redirect */
        $redirect = $this->staffOnly->handle($this->request, $next);

        $this->assertInstanceOf(RedirectResponse::class, $redirect);
        $this->assertSame(Response::HTTP_FOUND, $redirect->getStatusCode());
        $this->assertSame(route('auth::login'), $redirect->getTargetUrl());
    }

    /**
     * Should give 401 for an AJAX request without a user.
     */
    public function testGives401ForAjaxRequestWithoutUser()
    {
        $next = function () {};

        $this->requestIsAjax(true);

        $this->request
            ->shouldReceive('user')
            ->zeroOrMoreTimes()
            ->andReturn(null);

        $response = $this->staffOnly->handle($this->request, $next);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    /**
     * Should give 401 for a request from a non-staff user.
     */
    public function testGives401ForAjaxRequestFromNonStaffUser()
    {
        $next = function () {};

        $this->requestIsAjax(true);

        $user = $this->mockRequestUser();
        $user->shouldReceive('isStaff')->andReturn(false);

        $response = $this->staffOnly->handle($this->request, $next);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }
}
