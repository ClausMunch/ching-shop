<?php

namespace Testing\Unit\ChingShop\Image\Imagick;

use ChingShop\Image\Image;
use ChingShop\Image\Imagick\ImagePreProcessor;
use ChingShop\Image\Imagick\ImageTransformer;
use ChingShop\Image\Imagick\ImagickCollection;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class ImagePreProcessorTest extends TestWithImagick
{
    /** @var ImagePreProcessor */
    private $imagePreProcessor;

    /**
     * Set up image pre-processor for each test.
     */
    public function setUp()
    {
        parent::setUp();

        $this->imagePreProcessor = new ImagePreProcessor($this->imagick());
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(
            ImagePreProcessor::class,
            $this->imagePreProcessor
        );
    }

    /**
     * Should be able to pre-process an image resource into an imagick
     * container.
     */
    public function testPreProcess()
    {
        /** @var Image|MockObject $image */
        $image = $this->makeMock(Image::class);

        /** @var ImageTransformer|MockObject $transformer */
        $transformer = $this->makeMock(ImageTransformer::class);
        $transformer->expects($this->atLeastOnce())
            ->method('applyTo');
        $this->imagePreProcessor->addTransformer($transformer);

        $collection = $this->imagePreProcessor->preProcess($image);
        $this->assertInstanceOf(ImagickCollection::class, $collection);
    }
}
