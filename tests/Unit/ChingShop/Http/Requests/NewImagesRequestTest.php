<?php

namespace Testing\Unit\ChingShop\Http\Requests;

use ChingShop\Http\Requests\NewImagesRequest;

class NewImagesRequestTest extends RequestTest
{
    /** @var NewImagesRequest */
    private $newImagesRequest;

    /**
     * Set up persist product request for each test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->newImagesRequest = new NewImagesRequest();
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(
            NewImagesRequest::class,
            $this->newImagesRequest
        );
    }

    /**
     * Should have a rule for new images.
     */
    public function testRules()
    {
        $this->assertArrayHasKey(
            'new-image.*',
            $this->newImagesRequest->rules()
        );
    }
}
