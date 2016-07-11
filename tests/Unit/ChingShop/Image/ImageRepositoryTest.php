<?php

namespace Testing\Unit\ChingShop\Image;

use ChingShop\Catalogue\Product\Product;
use ChingShop\Events\NewImageEvent;
use ChingShop\Image\Image;
use ChingShop\Image\ImageRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Events\Dispatcher;
use Mockery\MockInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Testing\Unit\Behaviour\MocksModel;
use Testing\Unit\UnitTest;

/**
 * Class ImageRepositoryTest
 *
 * @package Testing\Unit\ChingShop\Image
 */
class ImageRepositoryTest extends UnitTest
{
    use MocksModel;

    /** @var ImageRepository */
    private $imageRepository;

    /** @var Image|MockInterface */
    private $imageResource;

    /** @var Dispatcher|MockObject */
    private $dispatcher;

    /**
     * Initialise image repository with mock dependencies.
     */
    public function setUp()
    {
        parent::setUp();

        $this->imageResource = $this->mockery(Image::class);
        $this->setMockModel($this->imageResource);

        $this->dispatcher = $this->makeMock(Dispatcher::class);

        $this->imageRepository = new ImageRepository(
            $this->imageResource,
            $this->dispatcher
        );
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(ImageRepository::class, $this->imageRepository);
    }

    /**
     * Should be able to load an image by ID.
     */
    public function testLoadById()
    {
        $id = $this->generator()->anyInteger();
        $image = $this->makeMock(Image::class);
        $this->imageResource->shouldReceive('where->first')
            ->atLeast()
            ->once()
            ->andReturn($image);

        $loaded = $this->imageRepository->loadById($id);

        $this->assertSame($image, $loaded);
    }

    /**
     * Should use image resource to persist uploaded image.
     */
    public function testMakesImageResource()
    {
        $fileName = $this->generator()->anyString();
        $upload = $this->makeMockUploadedFile();
        $upload->expects($this->atLeastOnce())
            ->method('move');
        $upload->expects($this->atLeastOnce())
            ->method('getClientOriginalName')
            ->willReturn($fileName);

        $newImage = $this->mockery(Image::class);
        $newImage->shouldReceive('getAttribute')
            ->andReturn($this->generator()->anyInteger());
        $newImage->shouldReceive('filename')->andReturn($fileName);
        $this->imageResource->shouldReceive('create')
            ->once()
            ->andReturn($newImage);

        $image = $this->imageRepository->storeUploadedImage($upload);

        $this->assertInstanceOf(Image::class, $image);
    }

    /**
     * Should move uploaded file to image directory
     * with resource id as filename.
     */
    public function testMovesUploadedFileUsingImageResourceID()
    {
        $upload = $this->makeMockUploadedFile();
        $upload->expects($this->atLeastOnce())
            ->method('getClientOriginalName');

        $filename = $this->generator()->anyString();
        $this->expectImageCreation($filename);

        $upload->expects($this->atLeastOnce())
            ->method('move')
            ->with(
                storage_path('image'),
                $this->isType('string')
            );

        $this->imageRepository->storeUploadedImage($upload);
    }

    /**
     * Should be able to load the latest image resources.
     */
    public function testLoadLatest()
    {
        $collection = new Collection([$this->makeMock(Image::class)]);
        $this->imageResource->shouldReceive('orderBy->limit->get')
            ->atLeast()
            ->once()
            ->andReturn($collection);

        $loaded = $this->imageRepository->loadLatest();

        $this->assertSame($collection, $loaded);
    }

    /**
     * Should be able to delete an image by ID.
     */
    public function testDeleteById()
    {
        $this->imageResource->shouldReceive('where->limit->delete')
            ->atLeast()
            ->once()
            ->andReturn(true);

        $deleted = $this->imageRepository->deleteById(
            $this->generator()->anyInteger()
        );

        $this->assertTrue($deleted);
    }

    /**
     * Should be able to trigger events to transfer local images.
     */
    public function testTransferLocalImages()
    {
        $image = $this->makeMock(Image::class);

        $this->imageResource->shouldReceive('orWhere->get')
            ->atLeast()
            ->once()
            ->andReturn(new Collection([$image]));

        $this->dispatcher->expects($this->atLeastOnce())
            ->method('fire')
            ->with($this->callback(
                function (NewImageEvent $event) use ($image) {
                    $this->assertSame($image, $event->image());

                    return true;
                }
            ));

        $this->imageRepository->transferLocalImages();
    }

    /**
     * @return MockObject|UploadedFile
     */
    private function makeMockUploadedFile(): MockObject
    {
        return $this->makeMock(UploadedFile::class);
    }

    /**
     * @param string $filename
     *
     * @return MockInterface
     */
    private function expectImageCreation(string $filename = null): MockInterface
    {
        if ($filename === null) {
            $filename = $this->generator()->anyInteger();
        }

        $newImage = $this->mockery(Image::class);
        $newImage->shouldReceive('getAttribute')
            ->with('filename')
            ->andReturn($filename);
        $newImage->shouldReceive('filename')->andReturn($filename);
        $newImage->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn($this->generator()->anyInteger());
        $this->imageResource->shouldReceive('create')
            ->andReturn($newImage);

        return $newImage;
    }
}
