<?php

namespace ChingShop\Image;

use ChingShop\Catalogue\Product\Product;
use Illuminate\Config\Repository as Config;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\FileBag;

class ImageRepository
{
    const IMAGE_DIR = 'image';

    /** @var Image */
    private $imageResource;

    /** @var Config */
    private $config;

    /**
     * ImageRepository constructor.
     *
     * @param Image  $imageResource
     * @param Config $config
     */
    public function __construct(Image $imageResource, Config $config)
    {
        $this->imageResource = $imageResource;
        $this->config = $config;
    }

    /**
     * @param UploadedFile $upload
     *
     * @return Image
     */
    public function storeUploadedImage(UploadedFile $upload): Image
    {
        $newImage = $this->imageResource->create([
            'filename' => $upload->getClientOriginalName(),
        ]);
        $upload->move(
            $this->config->get('filesystems.disks.local-public.root')
                .'/'
                .self::IMAGE_DIR,
            $newImage->filename
        );

        return $newImage;
    }

    /**
     * @param FileBag|UploadedFile[] $images
     * @param Product                $product
     */
    public function attachUploadedImagesToProduct($images, Product $product)
    {
        $product->attachImages(array_map(function (UploadedFile $image) {
            return $this->storeUploadedImage($image)->id;
        }, $images instanceof FileBag ? $images->all() : (array) $images));
    }
}
