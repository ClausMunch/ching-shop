<?php

namespace Testing\Unit\ChingShop\Http\Requests\Staff\Catalogue;

use ChingShop\Http\Requests\Staff\Catalogue\NewTagRequest;
use Testing\Unit\ChingShop\Http\Requests\RequestTest;

class NewTagRequestTest extends RequestTest
{
    /** @var NewTagRequest */
    private $newTagRequest;

    /**
     * Set up new tag request for each test.
     */
    public function setUp()
    {
        parent::setUp();

        $this->newTagRequest = new NewTagRequest();
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(NewTagRequest::class, $this->newTagRequest);
    }

    /**
     * Should have validation rules for a new tag.
     */
    public function testRules()
    {
        $this->assertArrayHasKey('name', $this->newTagRequest->rules());
    }
}
