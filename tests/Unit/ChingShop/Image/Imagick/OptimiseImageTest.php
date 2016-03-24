<?php

namespace Testing\Unit\ChingShop\Image\Imagick;

use ChingShop\Image\Imagick\ImagickCollection;
use ChingShop\Image\Imagick\OptimiseImage;
use Imagick;

class OptimiseImageTest extends TestWithImagick
{
    /** @var OptimiseImage */
    private $optimiseImage;

    /**
     * Set up optimise image transform for each test.
     */
    public function setUp()
    {
        parent::setUp();
        $this->optimiseImage = new OptimiseImage;
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(OptimiseImage::class, $this->optimiseImage);
    }

    /**
     * Should optimise an imagick collection.
     */
    public function testApplyTo()
    {
        $collection = new ImagickCollection([$this->imagick()]);

        $this->imagick()->expects($this->once())
            ->method('setInterlaceScheme')
            ->with(Imagick::INTERLACE_PLANE);

        $this->optimiseImage->applyTo($collection);
    }
}
