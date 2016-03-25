<?php

namespace Testing\Unit\ChingShop\Image\Imagick;

use ChingShop\Image\Imagick\ImagickAdapter;
use ChingShop\Image\Imagick\ImagickCollection;
use ChingShop\Image\Imagick\ImagickContract;
use ChingShop\Image\Imagick\WaterMark;
use Imagick;

class WaterMarkTest extends TestWithImagick
{
    /** @var WaterMark */
    private $waterMark;

    /** @var ImagickAdapter */
    private $waterMarkImage;

    /**
     * Set up water mark transform with imagick.
     */
    public function setUp()
    {
        parent::setUp();

        $this->waterMarkImage = new ImagickAdapter;
        $this->waterMark = new WaterMark($this->waterMarkImage);
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(WaterMark::class, $this->waterMark);
    }

    /**
     * Should apply the water mark to each image in the collection.
     */
    public function testApplyTo()
    {
        $image = $this->makeMock(ImagickContract::class);
        $images = new ImagickCollection([$image]);

        $image->expects($this->atLeastOnce())
            ->method('getImageWidth')
            ->willReturn(1024);

        $image->expects($this->once())
            ->method('compositeImage')
            ->with(
                $this->isInstanceOf(Imagick::class),
                $this->anything(),
                $this->anything(),
                $this->anything()
            );

        $this->waterMark->applyTo($images);
    }
}
