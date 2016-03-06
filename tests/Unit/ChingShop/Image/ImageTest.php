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
        $this->image->filename = $this->generator()->anySlug();
        $this->assertTrue($this->image->isInternal());
    }

    /**
     * Should be able to get the URL for an external image.
     */
    public function testUrlForExternalImage()
    {
        $this->image->filename = '';
        $this->image->url = $this->generator()->anySlug();
        $this->assertSame($this->image->url, $this->image->url());
    }

    /**
     * Sanity check filename method.
     */
    public function testFilename()
    {
        $this->image->filename = $this->generator()->anySlug();
        $this->assertSame($this->image->filename, $this->image->filename());
    }

    /**
     * Sanity check alt text method.
     */
    public function testAltText()
    {
        $this->image->alt_text = $this->generator()->anyString();
        $this->assertSame($this->image->alt_text, $this->image->altText());
    }
}
