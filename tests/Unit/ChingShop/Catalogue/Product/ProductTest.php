<?php

namespace Testing\Unit\ChingShop\Catalogue\Product;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

use Testing\Unit\UnitTest;
use ChingShop\Catalogue\Product\Product;

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
     * Should have a belongs-to-many relationship with Images
     */
    public function testImages()
    {
        $images = $this->product->images();
        $this->assertInstanceOf(BelongsToMany::class, $images);
        $this->assertEquals('images', $images->getRelationName());
    }

    /**
     * Should be able to attach images
     */
    public function testAttachImages()
    {
        /** @var BelongsToMany|MockObject $relationship */
        $relationship = $this->makeMock(BelongsToMany::class);
        $this->product->setImagesRelationship($relationship);

        $imageIDs = [
            $this->generator()->anyInteger(),
            $this->generator()->anyInteger()
        ];

        $relationship->expects($this->once())
            ->method('attach')
            ->with($imageIDs);

        $this->product->attachImages($imageIDs);
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
