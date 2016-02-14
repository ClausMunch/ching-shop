<?php

namespace Testing\Unit\ChingShop\Http\Middleware;

use Illuminate\Http\Request;

use ChingShop\Http\Middleware\EB_SSL_Trust;

class EB_SSL_TrustTest extends MiddlewareTest
{
    /** @var EB_SSL_Trust */
    private $ebSslTrust;

    /**
     * Create EB_SSL_Trust for each test
     */
    public function setUp()
    {
        parent::setUp();
        $this->ebSslTrust = new EB_SSL_Trust;
    }

    /**
     * Sanity check for instantiation
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(EB_SSL_Trust::class, $this->ebSslTrust);
    }

    /**
     * Should accept the client IP as a trusted proxy
     */
    public function testSetsClientIPAsTrustedProxy()
    {
        $passedOn = false;
        $next = function(Request $passedRequest) use (&$passedOn) {
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
