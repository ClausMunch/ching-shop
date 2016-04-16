<?php

namespace Testing\Unit\ChingShop\Http\Middleware;

use ChingShop\Http\Middleware\ForceSecure;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Symfony\Component\HttpFoundation\HeaderBag;

class ForceSecureTest extends MiddlewareTest
{
    /** @var ForceSecure */
    private $forceSecure;

    /** @var Redirector|MockObject */
    private $redirector;

    /**
     * Create ForceSecure middleware with dependencies for each test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->redirector = $this->makeMock(Redirector::class);
        $this->forceSecure = new ForceSecure($this->redirector);
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(ForceSecure::class, $this->forceSecure);
    }

    /**
     * Should redirect a non-secure request to a secure route.
     */
    public function testRedirectsNonSecureRequestToSecure()
    {
        $passedOn = false;
        $next = function (Request $passedRequest) use (&$passedOn) {
            $this->assertSame($passedRequest, $this->request);
            $passedOn = true;
        };

        $this->request->shouldReceive('isSecure')
            ->once()
            ->andReturn(false);
        $requestUri = 'request uri';
        $this->request->shouldReceive('getRequestUri')
            ->once()
            ->andReturn($requestUri);
        $this->request->headers = new HeaderBag([]);
        $this->redirector->expects($this->atLeastOnce())
            ->method('to')
            ->with(
                $requestUri,
                302,
                [],
                true
            );

        $this->forceSecure->handle($this->request, $next);

        $this->assertFalse($passedOn);
    }

    /**
     * Should do nothing with a secure request.
     */
    public function testPassesSecureRequestToNextHandler()
    {
        $passedOn = false;
        $next = function (Request $passedRequest) use (&$passedOn) {
            $this->assertSame($passedRequest, $this->request);
            $passedOn = true;
        };

        $this->request->shouldReceive('isSecure')
            ->once()
            ->andReturn(true);

        $this->forceSecure->handle($this->request, $next);

        $this->assertTrue($passedOn);
    }
}
