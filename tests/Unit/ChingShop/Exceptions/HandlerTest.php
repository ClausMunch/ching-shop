<?php

namespace Testing\Unit\ChingShop\Exceptions;

use Exception;
use Testing\Unit\UnitTest;
use Mockery\MockInterface;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use Illuminate\Http\Response;
use ChingShop\Exceptions\Handler;

class HandlerTest extends UnitTest
{
    /** @var Handler */
    private $handler;

    /** @var LoggerInterface|MockInterface */
    private $logger;

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

        $this->logger->shouldReceive('error')
            ->with($exception)
            ->once();

        $this->handler->report($exception);
    }

    /**
     * Should be able to render an exception into an HTTP response
     */
    public function testRender()
    {
        $exception = $this->makeMockException();

        /** @var Request|MockInterface $request */
        $request = $this->makeMock(Request::class);

        $response = $this->handler->render($request, $exception);

        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @return MockInterface|Exception
     */
    private function makeMockException(): MockInterface
    {
        return $this->makeMock(Exception::class);
    }
}
