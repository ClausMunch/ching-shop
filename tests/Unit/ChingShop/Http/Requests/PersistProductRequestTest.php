<?php

namespace Testing\Unit\ChingShop\Http\Requests;

use ChingShop\Http\Requests\PersistProductRequest;
use Illuminate\Http\Request as HttpRequest;
use Mockery\MockInterface;
use Testing\Unit\UnitTest;

class PersistProductRequestTest extends UnitTest
{
    /** @var PersistProductRequest */
    private $persistProductRequest;

    /** @var HttpRequest|MockInterface */
    private $httpRequest;

    public function setUp()
    {
        parent::setUp();

        $this->persistProductRequest = new PersistProductRequest();
        $this->httpRequest = $this->mockery(HttpRequest::class);
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
        $userIsStaff = $this->generator()->anyBoolean();
        $this->httpRequest->shouldReceive('user->isStaff')
            ->andReturn($userIsStaff);

        $authorised = $this->persistProductRequest->authorize(
            $this->httpRequest
        );

        $this->assertSame($userIsStaff, $authorised);
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

        foreach ($rules as $ruleSet) {
            $this->assertContains('unique:products', explode('|', $ruleSet));
        }
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
