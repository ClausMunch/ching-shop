<?php

namespace Testing\Unit\ChingShop\Http\Requests;

use ChingShop\Http\Requests\Request;
use Illuminate\Http\Request as HttpRequest;
use Mockery\MockInterface;
use Testing\Unit\UnitTest;

abstract class RequestTest extends UnitTest
{
    /** @var HttpRequest|MockInterface */
    protected $httpRequest;

    /**
     * Set up mock HTTP request for each test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->httpRequest = $this->mockery(HttpRequest::class);
    }

    /**
     * @param Request $request
     */
    protected function assertRequestAuthorisesOnStaff(Request $request)
    {
        $userIsStaff = $this->generator()->anyBoolean();
        $this->httpRequest->shouldReceive('user->isStaff')
            ->andReturn($userIsStaff);

        $authorised = $request->authorize($this->httpRequest);

        $this->assertSame($userIsStaff, $authorised);
    }
}
