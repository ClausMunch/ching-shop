<?php

namespace Testing\Unit\ChingShop\Http;

use Testing\Unit\UnitTest;
use ChingShop\Http\Kernel;
use Mockery\MockInterface;
use Illuminate\Routing\Router;
use Illuminate\Contracts\Foundation\Application;

class KernelTest extends UnitTest
{
    /**
     * Sanity test for instantiating HTTP Kernel
     */
    public function testCanInstantiate()
    {
        /** @var Application|MockInterface $application */
        $application = $this->mockery(Application::class);

        /** @var Router|MockInterface $router */
        $router = $this->mockery(Router::class);
        $router->shouldReceive('middleware')->zeroOrMoreTimes();
        $router->shouldReceive('middlewareGroup')->zeroOrMoreTimes();

        $kernel = new Kernel($application, $router);
        $this->assertInstanceOf(Kernel::class, $kernel);
    }
}
