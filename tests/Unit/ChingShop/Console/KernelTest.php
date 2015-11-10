<?php

namespace Testing\Unit\ChingShop\Console;

use Testing\Unit\UnitTest;
use Mockery\MockInterface;
use ChingShop\Console\Kernel;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;

class KernelTest extends UnitTest
{
    /**
     * Sanity check that console kernel can be instantiated
     */
    public function testCanInstantiate()
    {
        /** @var Application|MockInterface $application */
        $application = $this->makeMock(Application::class);
        $application->shouldReceive('booted')->zeroOrMoreTimes();

        /** @var Dispatcher|MockInterface $dispatcher */
        $dispatcher = $this->makeMock(Dispatcher::class);

        $kernel = new Kernel($application, $dispatcher);
        $this->assertInstanceOf(Kernel::class, $kernel);
    }
}
