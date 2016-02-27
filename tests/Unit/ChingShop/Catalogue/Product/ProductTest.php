<?php

namespace Testing\Unit\ChingShop\Catalogue\Product;

use ChingShop\Catalogue\Product\Product;
use Testing\Unit\UnitTest;

class ProductTest extends UnitTest
{
    /** @var Product */
    private $product;

    /**
     * Initialise product for each test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->product = new Product();
    }

    /**
     * Sanity check for instantiation.
     */
    public function testCanInstantiate()
    {
        $this->assertInstanceOf(Product::class, $this->product);
    }

    /**
     * isStored should return false when there is no id.
     */
    public function testIsStoredIsFalseWhenNoId()
    {
        $this->product->id = null;
        $this->assertFalse($this->product->isStored());
    }

    /**
     * isStored should return true when id is set.
     */
    public function testIsStoredIsTrueWhenIdIsPresent()
    {
        $this->product->id = abs($this->generator()->anyInteger()) + 1;
        $this->assertTrue($this->product->isStored());
    }
}
