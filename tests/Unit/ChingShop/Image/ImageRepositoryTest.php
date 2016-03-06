<?php

namespace Testing\Unit\ChingShop\Image;

use Mockery\MockInterface;
use Testing\Unit\UnitTest;
use ChingShop\Image\Image;
use ChingShop\Image\ImageRepository;
use Testing\Unit\Behaviour\MocksModel;
use ChingShop\Catalogue\Product\Product;
use Illuminate\Config\Repository as Config;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class ImageRepositoryTest extends UnitTest
{
    use MocksModel;

    /** @var ImageRepository */
    private $imageRepository;

    /** @var Image|MockInterface */
    private $imageResource;

    /** @var Config|MockInterface */
    private $config;

    /**
     * Initialise image repository with mock dependencies.
     */
    public function setUp()
    {
        parent::setUp();

        $this->imageResource = $this->mockery(Image::class);
        $this->setMockModel($this->imageResource);

        $this->config = $this->mockery(Config::class);

        $this->imageRepository = new ImageRepository(
            $this->imageResource,
            $this->config
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
    public function testMustLoadById()
    {
        $id = $this->generator()->anyInteger();
        $image = $this->makeMock(Image::class);
        $this->imageResource->shouldReceive('where->limit->first')
            ->atLeast()->once()
            ->andReturn($image);

        $loaded = $this->imageRepository->mustLoadById($id);

        $this->assertSame($image, $loaded);
    }

    /**
     * Should use image resource to persist uploaded image.
     */
    public function testMakesImageResource()
    {
        $this->config->shouldReceive('get');

        $fileName = $this->generator()->anyString();
        $upload = $this->makeMockUploadedFile();
        $upload->expects($this->once())
            ->method('move');
        $upload->expects($this->once())
            ->method('getClientOriginalName')
            ->willReturn($fileName);

        $newImage = $this->mockery(Image::class);
        $newImage->shouldReceive('getAttribute')
            ->andReturn($this->generator()->anyInteger());
        $this->imageResource->shouldReceive('create')
            ->once()
            ->with([
                'filename' => $fileName,
            ])
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
        $upload->expects($this->once())
            ->method('getClientOriginalName');

        $filename = $this->generator()->anyString();
        $this->expectImageCreation($filename);

        $storagePath = $this->generator()->anyString();
        $this->config->shouldReceive('get')
            ->with('filesystems.disks.local-public.root')
            ->andReturn($storagePath);

        $upload->expects($this->once())
            ->method('move')
            ->with(
                $storagePath.'/image',
                $this->isType('string')
            );

        $this->imageRepository->storeUploadedImage($upload);
    }

    /**
     * Should be able to link uploaded images to a product.
     */
    public function testAttachesUploadedImagesToProduct()
    {
        $uploadedFile = $this->makeMockUploadedFile();
        $uploadedFile->expects($this->once())
            ->method('getClientOriginalName')
            ->willReturn($this->generator()->anyString());
        $uploadedFile->expects($this->once())
            ->method('move');

        $imageBag = $this->makeMockFileBag([$uploadedFile]);
        $product = $this->makeMockProduct();

        $product->shouldReceive('attachImages')
            ->once()
            ->with(\Mockery::type('array'));

        $this->config->shouldReceive('get');

        $this->expectImageCreation();

        $this->imageRepository->attachUploadedImagesToProduct(
            $imageBag,
            $product
        );
    }

    /**
     * Should be able to detach and image from a product.
     */
    public function testDetachImageFromProduct()
    {
        $image = $this->imageResource;
        $product = $this->makeMockProduct();

        $image->shouldReceive('getAttribute')
            ->atLeast()
            ->once()
            ->andReturn($this->generator()->anyInteger());

        /** @var BelongsToMany|MockObject $imagesRelation */
        $imagesRelation = $this->makeMock(BelongsToMany::class);

        $product->shouldReceive('images')
            ->once()
            ->andReturn($imagesRelation);

        $imagesRelation->expects($this->once())
            ->method('detach')
            ->with($image->id);

        $this->imageRepository->detachImageFromProduct($image, $product);
    }

    /**
     * @return MockObject|UploadedFile
     */
    private function makeMockUploadedFile(): MockObject
    {
        $uploadedFile = $this->makeMock(UploadedFile::class);

        return $uploadedFile;
    }

    /**
     * @param UploadedFile[] $files
     *
     * @return MockInterface|FileBag
     */
    private function makeMockFileBag(array $files = []): MockInterface
    {
        $fileBag = $this->mockery(FileBag::class);
        $fileBag->shouldReceive('all')->andReturn($files);

        return $fileBag;
    }

    /**
     * @return MockInterface|Product
     */
    private function makeMockProduct(): MockInterface
    {
        return $this->mockery(Product::class);
    }

    /**
     * @param string $filename
     *
     * @return MockInterface
     */
    private function expectImageCreation(string $filename = null): MockInterface
    {
        if (is_null($filename)) {
            $filename = $this->generator()->anyInteger();
        }

        $newImage = $this->mockery(Image::class);
        $newImage->shouldReceive('getAttribute')
            ->with('filename')
            ->andReturn($filename);
        $newImage->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn($this->generator()->anyInteger());
        $this->imageResource->shouldReceive('create')
            ->andReturn($newImage);

        return $newImage;
    }
}
