<?php

namespace Testing\Unit\ChingShop\Image;

use Testing\Unit\UnitTest;
use Mockery\MockInterface;
use Testing\Unit\Behaviour\MocksModel;

use ChingShop\Image\Image;
use ChingShop\Image\ImageRepository;
use ChingShop\Catalogue\Product\Product;

use Illuminate\Config\Repository as Config;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     * Initialise image repository with mock dependencies
     */
    public function setUp()
    {
        parent::setUp();

        $this->imageResource = $this->makeMock(Image::class);
        $this->setMockModel($this->imageResource);

        $this->config = $this->makeMock(Config::class);

        $this->imageRepository = new ImageRepository(
            $this->imageResource,
            $this->config
        );
    }

    /**
     * Sanity check for instantiation
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(ImageRepository::class, $this->imageRepository);
    }

    /**
     * Should use image resource to persist uploaded image
     */
    public function testMakesImageResource()
    {
        $this->config->shouldReceive('get');

        $fileName = $this->generator()->anyString();
        $upload = $this->makeMockUploadedFile();
        $upload->shouldReceive('move');
        $upload->shouldReceive('getClientOriginalName')
            ->andReturn($fileName);

        $newImage = $this->makeMock(Image::class);
        $newImage->shouldReceive('getAttribute')
            ->andReturn($this->generator()->anyInteger());
        $this->imageResource->shouldReceive('create')
            ->once()
            ->with([
                'filename' => $fileName
            ])
            ->andReturn($newImage);

        $image = $this->imageRepository->storeUploadedImage($upload);

        $this->assertInstanceOf(Image::class, $image);
    }

    /**
     * Should move uploaded file to image directory
     * with resource id as filename
     */
    public function testMovesUploadedFileUsingImageResourceID()
    {
        $upload = $this->makeMockUploadedFile();
        $upload->shouldReceive('getClientOriginalName');

        $filename = $this->generator()->anyString();
        $this->expectImageCreation($filename);

        $storagePath = $this->generator()->anyString();
        $this->config->shouldReceive('get')
            ->with('filesystems.disks.local-public.root')
            ->andReturn($storagePath);

        $upload->shouldReceive('move')
            ->once()
            ->with(
                $storagePath . '/image',
                \Mockery::type('string')
            );

        $this->imageRepository->storeUploadedImage($upload);
    }

    /**
     * Should be able to link uploaded images to a product
     */
    public function testAttachesUploadedImagesToProduct()
    {
        $uploadedFile = $this->makeMockUploadedFile();
        $uploadedFile->shouldReceive('getClientOriginalName')
            ->andReturn($this->generator()->anyString());
        $uploadedFile->shouldReceive('move')->once();

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
     * @return MockInterface|UploadedFile
     */
    private function makeMockUploadedFile(): MockInterface
    {
        $uploadedFile = $this->makeMock(UploadedFile::class);
        return $uploadedFile;
    }

    /**
     * @param UploadedFile[] $files
     * @return MockInterface|FileBag
     */
    private function makeMockFileBag(array $files = []): MockInterface
    {
        $fileBag = $this->makeMock(FileBag::class);
        $fileBag->shouldReceive('all')->andReturn($files);
        return $fileBag;
    }

    /**
     * @return MockInterface|Product
     */
    private function makeMockProduct(): MockInterface
    {
        return $this->makeMock(Product::class);
    }

    /**
     * @param string $filename
     * @return MockInterface
     */
    private function expectImageCreation(string $filename = null): MockInterface
    {
        if (is_null($filename)) {
            $filename = $this->generator()->anyInteger();
        }

        $newImage = $this->makeMock(Image::class);
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
