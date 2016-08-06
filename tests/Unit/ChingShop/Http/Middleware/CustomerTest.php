<?php

namespace Testing\Unit\ChingShop\Http\Middleware;

use ChingShop\Http\Middleware\Customer;
use ChingShop\Modules\Sales\Model\Clerk;
use Illuminate\Http\Request;
use Testing\Unit\MockObject;

class CustomerTest extends MiddlewareTest
{
    /** @var Customer */
    private $customerMiddleware;

    /** @var Clerk|MockObject */
    private $clerk;

    /**
     * Set up customer middleware with mock dependencies.
     */
    public function setUp()
    {
        parent::setUp();

        $this->clerk = $this->makeMock(Clerk::class);
        $this->customerMiddleware = new Customer($this->clerk);
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
