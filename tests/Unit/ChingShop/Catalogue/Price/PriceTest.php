<?php

namespace Testing\Unit\ChingShop\Catalogue\Price;

use ChingShop\Catalogue\Price\Price;
use Testing\Unit\UnitTest;

class PriceTest extends UnitTest
{
    /** @var Price */
    private $price;

    /**
     * Set up price for each test.
     */
    public function setUp()
    {
        $this->price = new Price;
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(Price::class, $this->price);
    }

    /**
     * Should be able to get the whole price formatted.
     */
    public function testFormat()
    {
        $this->price->units = 10;
        $this->price->subunits = 99;
        $this->assertEquals('£10.99', $this->price->formatted());
    }

    /**
     * Should be able to get the subunits formatted.
     */
    public function testSubUnitsFormatted()
    {
        $this->price->subunits = 5;
        $this->assertEquals('05', $this->price->subUnitsFormatted());
    }
}
