<?php

namespace Testing\Unit\ChingShop\Exceptions;

use Exception;
use Testing\Unit\UnitTest;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use Illuminate\Http\Response;
use ChingShop\Exceptions\Handler;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class HandlerTest extends UnitTest
{
    /** @var Handler */
    private $handler;

    /** @var LoggerInterface|MockObject */
    private $logger;

    /**
     * Initialise handler for each test
     */
    public function setUp()
    {
        parent::setUp();
        $this->logger = $this->makeMock(LoggerInterface::class);
        $this->handler = new Handler($this->logger);
    }

    /**
     * Sanity check that exception handler can be instantiated
     */
    public function testCanInstantiate()
    {
        $this->assertInstanceOf(Handler::class, $this->handler);
    }

    /**
     * Should be able to report exception
     */
    public function testReport()
    {
        $exception = $this->makeMockException();
        $this->logger->expects($this->once())
            ->method('error')
            ->with($exception);
        $this->handler->report($exception);
    }

    /**
     * Should be able to render an exception into an HTTP response
     */
    public function testRender()
    {
        $exception = $this->makeMockException();

        /** @var Request|MockObject $request */
        $request = $this->mockery(Request::class);

        $response = $this->handler->render($request, $exception);

        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @return MockObject|Exception
     */
    private function makeMockException(): MockObject
    {
        return $this->makeMock(Exception::class);
    }
}
