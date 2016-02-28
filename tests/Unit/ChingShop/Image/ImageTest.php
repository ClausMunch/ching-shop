<?php

namespace Testing\Unit\ChingShop\Image;

use ChingShop\Image\Image;
use Testing\Unit\UnitTest;

class ImageTest extends UnitTest
{
    /** @var Image */
    private $image;

    /**
     * Set up image for each test.
     */
    public function setUp()
    {
        $this->image = new Image();
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(Image::class, $this->image);
    }

    /**
     * Is internal should be true if the image has a filename.
     */
    public function testIsInternal()
    {
        $this->image->filename = 'foo file';
        $this->assertTrue($this->image->isInternal());
    }
}
