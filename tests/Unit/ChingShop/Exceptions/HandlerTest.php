<?php

namespace Testing\Unit\ChingShop\Exceptions;

use ChingShop\Exceptions\Handler;
use Exception;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Psr\Log\LoggerInterface;
use Testing\Unit\UnitTest;

/**
 * Class HandlerTest
 * @package Testing\Unit\ChingShop\Exceptions
 */
class HandlerTest extends UnitTest
{
    /** @var Handler */
    private $handler;

    /** @var Container */
    private $container;

    /** @var LoggerInterface|MockObject */
    private $logger;

    /**
     * Initialise handler for each test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->logger = $this->makeMock(LoggerInterface::class);
        $this->container = new \Illuminate\Container\Container();
        $this->container[LoggerInterface::class] = $this->logger;
        $this->handler = new Handler($this->container);
    }

    /**
     * Sanity check that exception handler can be instantiated.
     */
    public function testCanInstantiate()
    {
        $this->assertInstanceOf(Handler::class, $this->handler);
    }

    /**
     * Should be able to report exception.
     */
    public function testReport()
    {
        $exception = new Exception();
        $this->logger->expects($this->atLeastOnce())
            ->method('error')
            ->with($exception);
        $this->handler->report($exception);
    }

    /**
     * Should be able to render an exception into an HTTP response.
     */
    public function testRender()
    {
        $exception = new Exception();

        /** @var Request|MockObject $request */
        $request = $this->mockery(Request::class);

        $response = $this->handler->render($request, $exception);

        $this->assertInstanceOf(Response::class, $response);
    }
}
