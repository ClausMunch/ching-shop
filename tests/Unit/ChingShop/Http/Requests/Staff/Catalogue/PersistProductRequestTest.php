<?php

namespace Testing\Unit\ChingShop\Http\Requests\Staff\Catalogue;

use ChingShop\Http\Requests\Staff\Catalogue\Product\PersistProductRequest;
use Illuminate\Http\Request as HttpRequest;
use Testing\Unit\ChingShop\Http\Requests\RequestTest;

class PersistProductRequestTest extends RequestTest
{
    /** @var PersistProductRequest */
    private $persistProductRequest;

    /**
     * Set up persist product request for each test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->persistProductRequest = new PersistProductRequest();
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(
            PersistProductRequest::class,
            $this->persistProductRequest
        );
    }

    /**
     * Authorisation is equivalent to whether the user is staff.
     */
    public function testAuthorize()
    {
        $this->assertRequestAuthorisesOnStaff($this->persistProductRequest);
    }

    /**
     * If the request is to create a new product
     * the rules should contain a plain unique constraint.
     */
    public function testRulesAreUniqueForCreateRequest()
    {
        $this->httpRequest->shouldReceive('method')->andReturn(
            HttpRequest::METHOD_POST
        );

        $rules = $this->persistProductRequest->rules($this->httpRequest);

        $this->assertContains('unique:products', explode('|', $rules['name']));
    }

    /**
     * If the request is to update an existing product
     * the rules should contain a unique constraint excluding
     * the existing product.
     */
    public function testRulesExcludeExistingForUpdateRequest()
    {
        $this->httpRequest->shouldReceive('method')->andReturn(
            $this->generator()->anyOneOf([
                HttpRequest::METHOD_PUT,
                HttpRequest::METHOD_PATCH,
            ])
        );

        $ID = $this->generator()->anyInteger();
        $this->httpRequest->shouldReceive('get')
            ->with('id')
            ->andReturn($ID);

        $rules = $this->persistProductRequest->rules($this->httpRequest);

        foreach (['name', 'sku'] as $ruleSetKey) {
            $this->assertContains(
                "unique:products,{$ruleSetKey},{$ID}",
                explode('|', $rules[$ruleSetKey])
            );
        }
    }
}
