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
        parent::setUp();
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

    /**
     * Internal images should give a secure asset path.
     */
    public function testUrlForInternalImage()
    {
        $image = new Image([
            'filename' => 'foo.jpg',
        ]);

        $parts = parse_url($image->url());

        $this->assertEquals('https', $parts['scheme']);
        $this->assertContains('ching-shop', $parts['host']);
        $this->assertContains('foo.jpg', $parts['path']);
    }

    /**
     * Should be able to get a sized URL.
     */
    public function testUrlWithSize()
    {
        $image = new Image([
            'url' => 'https://www.ching-shop.dev/image/foo.jpg',
        ]);

        $this->assertEquals(
            'https://www.ching-shop.dev/image/foo-large.jpg',
            $image->url('large')
        );
    }

    /**
     * Should be able to get the src-set attribute for the image.
     */
    public function testSrcSet()
    {
        $image = new Image([
            'url' => 'https://www.ching-shop.dev/image/foo.jpg',
        ]);

        $this->assertRegExp(
            '/(https:\/\/(.*?)ching-shop(.*?)foo(.*?) [0-9]+w,?)+/',
            $image->srcSet()
        );
    }

    /**
     * Should be able to get a location glyph string for the image.
     */
    public function testLocationGlyph()
    {
        $image = new Image([
            'url' => 'https://www.ching-shop.dev/image/foo.jpg',
        ]);
        $this->assertInternalType('string', $image->locationGlyph());
    }

    /**
     * Should be able to get the image storage location.
     */
    public function testStorageLocation()
    {
        $image = new Image(['filename' => 'foo.jpg']);
        $this->assertInternalType('string', $image->storageLocation());
    }

    /**
     * Should be able to determine whether the image resource has been saved.
     */
    public function testIsStored()
    {
        $image = new Image();
        $image->id = 123;
        $this->assertTrue($image->isStored());
    }

    /**
     * Should be able to get the routing name prefix for persisting this
     * resource.
     */
    public function testRoutePath()
    {
        $this->assertEquals('staff.products.images', $this->image->routePath());
    }

    /**
     * Should be able to get the CRUD id for the image.
     */
    public function testCrudID()
    {
        $image = new Image();
        $image->id = 123;
        $this->assertEquals($image->id, $image->crudId());
    }
}
