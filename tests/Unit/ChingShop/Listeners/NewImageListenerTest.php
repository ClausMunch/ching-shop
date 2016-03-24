<?php

namespace Testing\Unit\ChingShop\Listeners;

use ChingShop\Events\NewImageEvent;
use ChingShop\Image\Image;
use ChingShop\Image\Imagick\ImagePreProcessor;
use ChingShop\Image\Imagick\ImagickCollection;
use ChingShop\Image\Imagick\ImagickContract;
use ChingShop\Listeners\NewImageListener;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\FilesystemInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Testing\Unit\UnitTest;

class NewImageListenerTest extends UnitTest
{
    /** @var NewImageListener */
    private $newImageListener;

    /** @var ImagePreProcessor|MockObject */
    private $imagePreProcessor;

    /** @var FilesystemAdapter|MockObject */
    private $publicFilesystem;

    /** @var FilesystemInterface|MockObject */
    private $filesystem;

    /** @var Repository|MockObject */
    private $config;

    /** @var Image|MockObject */
    private $image;

    /** @var NewImageEvent */
    private $event;

    /**
     * Set up new image listener with mock dependencies.
     */
    public function setUp()
    {
        parent::setUp();

        $this->imagePreProcessor = $this->makeMock(ImagePreProcessor::class);
        $this->config = $this->makeMock(Repository::class);

        $this->publicFilesystem = $this->makeMock(FilesystemAdapter::class);
        $this->filesystem = $this->makeMock(FilesystemInterface::class);
        $this->publicFilesystem->expects($this->any())
            ->method('getDriver')
            ->willReturn($this->filesystem);

        $this->newImageListener = new NewImageListener(
            $this->imagePreProcessor,
            $this->publicFilesystem,
            $this->config
        );

        $this->image = $this->makeMock(Image::class);
        $this->event = new NewImageEvent($this->image);
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(
            NewImageListener::class,
            $this->newImageListener
        );
    }

    /**
     * Should unset the filename attribute for a missing file.
     */
    public function testHandleMissingImage()
    {
        $this->image->expects($this->once())
            ->method('setAttribute')
            ->with('filename', '');
        $this->image->expects($this->once())
            ->method('save');

        $this->newImageListener->handle($this->event);
    }

    /**
     * Should pre-process and transfer an image.
     */
    public function testHandle()
    {
        $filename = '/tmp/'.uniqid();
        file_put_contents($filename, '');
        $this->image->expects($this->atLeastOnce())
            ->method('storageLocation')
            ->willReturn($filename);

        $preProcessed = $this->makeMock(ImagickContract::class);
        $this->imagePreProcessor->expects($this->once())
            ->method('preProcess')
            ->with($this->image)
            ->willReturn(new ImagickCollection([$preProcessed]));

        $this->config->expects($this->atLeastOnce())
            ->method('get')
            ->willReturn('foobar_endpoint');

        $fileContent = 'foo bar';
        $preProcessed->expects($this->once())
            ->method('getImageBlob')
            ->willReturn($fileContent);

        $this->filesystem->expects($this->once())
            ->method('put')
            ->with(
                $this->isType('string'),
                $fileContent,
                $this->isType('array')
            );

        $this->newImageListener->handle($this->event);
    }
}
