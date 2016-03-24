<?php

namespace Testing\Unit\ChingShop\Image\Imagick;

use ChingShop\Image\Imagick\ImagickCollection;
use ChingShop\Image\Imagick\ImagickContract;
use ChingShop\Image\Imagick\WaterMark;

class WaterMarkTest extends TestWithImagick
{
    /** @var WaterMark */
    private $waterMark;

    /**
     * Set up water mark transform with mock imagick.
     */
    public function setUp()
    {
        parent::setUp();

        $this->waterMark = new WaterMark($this->waterMark());
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

        $this->waterMark()->expects($this->atLeastOnce())
            ->method('count')
            ->willReturn(0);

        $image->expects($this->once())
            ->method('compositeImage')
            ->with(
                $this->waterMark(),
                $this->anything(),
                $this->anything(),
                $this->anything()
            );

        $this->waterMark->applyTo($images);
    }

    /**
     * (Make it clearer what the imagick field is in this test).
     * @return ImagickContract|\PHPUnit_Framework_MockObject_MockObject
     */
    private function waterMark()
    {
        return $this->imagick();
    }
}
