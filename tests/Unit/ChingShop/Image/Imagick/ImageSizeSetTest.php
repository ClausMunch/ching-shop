<?php

namespace Testing\Unit\ChingShop\Image\Imagick;

use ChingShop\Image\Imagick\ImageSizeSet;
use ChingShop\Image\Imagick\ImagickCollection;

class ImageSizeSetTest extends TestWithImagick
{
    /** @var ImageSizeSet */
    private $imageSizeSet;

    /**
     * Set up image size set for each test;
     */
    public function setUp()
    {
        parent::setUp();

        $this->imageSizeSet = new ImageSizeSet;
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(ImageSizeSet::class, $this->imageSizeSet);
    }

    /**
     * Should add further image sizes to the collection.
     */
    public function testApplyTo()
    {
        $collection = new ImagickCollection([$this->imagick()]);

        $this->imagick()->expects($this->any())
            ->method('getFilename')
            ->willReturn('foobar-image.jpg');

        $this->imagick()->expects($this->atLeastOnce())
            ->method('scaleImage');
        $this->imagick()->expects($this->atLeastOnce())
            ->method('setFilename');

        $this->imageSizeSet->applyTo($collection);
    }
}
