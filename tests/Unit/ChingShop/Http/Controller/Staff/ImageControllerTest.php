<?php

namespace Testing\Unit\ChingShop\Http\Controller\Staff;

use ChingShop\Image\ImageRepository;
use ChingShop\Modules\Catalogue\Http\Controllers\Staff\ImageController;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Testing\Unit\ChingShop\Http\Controller\ControllerTest;

class ImageControllerTest extends ControllerTest
{
    /** @var \ChingShop\Modules\Catalogue\Http\Controllers\Staff\ImageController */
    private $imageController;

    /** @var ImageRepository|MockObject */
    private $imageRepository;

    /**
     * Set up image controller with mock dependencies.
     */
    public function setUp()
    {
        $this->imageRepository = $this->makeMock(ImageRepository::class);

        $this->imageController = new \ChingShop\Modules\Catalogue\Http\Controllers\Staff\ImageController(
            $this->imageRepository,
            $this->webUi()
        );
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(ImageController::class, $this->imageController);
    }

    /**
     * Should be able to delete an image by id.
     */
    public function testDestroy()
    {
        $imageId = $this->generator()->anyInteger();
        $this->imageRepository
            ->expects($this->atLeastOnce())
            ->method('deleteById')
            ->with($imageId);

        $this->imageController->destroy($imageId);
    }

    /**
     * Should be able to request that local images are transferred to cloud
     * storage.
     */
    public function testTransferLocalImages()
    {
        $this->imageRepository
            ->expects($this->atLeastOnce())
            ->method('transferLocalImages');

        $this->imageController->transferLocalImages();
    }
}
