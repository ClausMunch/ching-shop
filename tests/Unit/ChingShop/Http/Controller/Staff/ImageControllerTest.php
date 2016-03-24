<?php

namespace Testing\Unit\ChingShop\Http\Controller\Staff;

use ChingShop\Http\Controllers\Staff\ImageController;
use ChingShop\Image\ImageRepository;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Illuminate\Http\RedirectResponse;
use Testing\Unit\ChingShop\Http\Controller\ControllerTest;

class ImageControllerTest extends ControllerTest
{
    /** @var ImageController */
    private $imageController;

    /** @var ImageRepository|MockObject */
    private $imageRepository;

    /**
     * Set up image controller with mock dependencies.
     */
    public function setUp()
    {
        $this->imageRepository = $this->makeMock(ImageRepository::class);

        $this->imageController = new ImageController(
            $this->viewFactory(),
            $this->responseFactory(),
            $this->imageRepository
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
        $this->imageRepository->expects($this->once())
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
        $this->imageRepository->expects($this->once())
            ->method('transferLocalImages');

        $this->imageController->transferLocalImages();
    }
}
