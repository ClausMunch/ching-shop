<?php

namespace Testing\Unit\ChingShop\Http\Requests\Staff\Catalogue;

use ChingShop\Http\Requests\Staff\Catalogue\NewImagesRequest;
use Testing\Unit\ChingShop\Http\Requests\RequestTest;

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
