<?php

namespace Testing\Unit\ChingShop\Http\Requests;

use ChingShop\Http\Requests\SetPriceRequest;

class SetPriceRequestTest extends RequestTest
{
    /** @var SetPriceRequest */
    private $setPriceRequest;

    /**
     * Set up set price request for each test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->setPriceRequest = new SetPriceRequest;
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(SetPriceRequest::class, $this->setPriceRequest);
    }

    /**
     * Should authorize based on whether the user is staff.
     */
    public function testAuthorize()
    {
        $this->assertRequestAuthorisesOnStaff($this->setPriceRequest);
    }

    /**
     * Should specify validation rules for price units and subunits.
     */
    public function testRules()
    {
        $this->assertArrayHasKey('units', $this->setPriceRequest->rules());
        $this->assertArrayHasKey('subunits', $this->setPriceRequest->rules());
    }
}
