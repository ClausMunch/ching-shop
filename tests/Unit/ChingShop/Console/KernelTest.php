<?php

namespace Testing\Unit\ChingShop\Console;

use ChingShop\Console\Kernel;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Mockery\MockInterface;
use Testing\Unit\UnitTest;

class KernelTest extends UnitTest
{
    /**
     * Sanity check that console kernel can be instantiated.
     */
    public function testCanInstantiate()
    {
        /** @var Application|MockInterface $application */
        $application = $this->mockery(Application::class);
        $application->shouldReceive('booted')->zeroOrMoreTimes();

        /** @var Dispatcher|MockInterface $dispatcher */
        $dispatcher = $this->mockery(Dispatcher::class);

        $kernel = new Kernel($application, $dispatcher);
        $this->assertInstanceOf(Kernel::class, $kernel);
    }
}
