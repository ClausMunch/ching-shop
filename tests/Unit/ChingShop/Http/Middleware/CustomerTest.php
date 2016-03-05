<?php

namespace Testing\Unit\ChingShop\Http\Middleware;

use ChingShop\Http\Middleware\Customer;
use Illuminate\Http\Request;

class CustomerTest extends MiddlewareTest
{
    /** @var Customer */
    private $customerMiddleware;

    /**
     * Set up customer middleware with mock dependencies.
     */
    public function setUp()
    {
        parent::setUp();

        $this->customerMiddleware = new Customer();
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(Customer::class, $this->customerMiddleware);
    }

    /**
     * Should pass on the request after adding the location composer.
     */
    public function testHandle()
    {
        $passedOn = false;
        $next = function (Request $passedRequest) use (&$passedOn) {
            $this->assertSame($passedRequest, $this->request);
            $passedOn = true;
        };

        $this->customerMiddleware->handle($this->request, $next);

        $this->assertTrue($passedOn);
    }
}
