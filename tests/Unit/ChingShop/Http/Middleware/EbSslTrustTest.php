<?php

namespace Testing\Unit\ChingShop\Http\Middleware;

use ChingShop\Http\Middleware\EbSslTrust;
use Illuminate\Http\Request;

class EbSslTrustTest extends MiddlewareTest
{
    /** @var EbSslTrust */
    private $ebSslTrust;

    /**
     * Create EbSslTrust for each test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->ebSslTrust = new EbSslTrust();
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(EbSslTrust::class, $this->ebSslTrust);
    }

    /**
     * Should accept the client IP as a trusted proxy.
     */
    public function testSetsClientIPAsTrustedProxy()
    {
        $passedOn = false;
        $next = function (Request $passedRequest) use (&$passedOn) {
            $this->assertSame($passedRequest, $this->request);
            $passedOn = true;
        };

        $clientIP = 'client ip';
        $this->request->shouldReceive('getClientIp')
            ->once()
            ->andReturn($clientIP);
        $this->request->shouldReceive('setTrustedProxies')
            ->once()
            ->with([$clientIP]);

        $this->ebSslTrust->handle($this->request, $next);

        $this->assertTrue($passedOn);
    }
}
