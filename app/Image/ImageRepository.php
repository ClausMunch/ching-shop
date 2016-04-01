<?php

namespace ChingShop\Image;

use ChingShop\Catalogue\Product\Product;
use ChingShop\Events\NewImageEvent;
use Illuminate\Config\Repository as Config;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Events\Dispatcher;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\FileBag;

class ImageRepository
{
    /** @var Image|Builder */
    private $imageResource;

    /** @var Config */
    private $config;

    /** @var Dispatcher */
    private $dispatcher;

    /**
     * ImageRepository constructor.
     *
     * @param Image      $imageResource
     * @param Config     $config
     * @param Dispatcher $dispatcher
     */
    public function __construct(
        Image $imageResource,
        Config $config,
        Dispatcher $dispatcher
    ) {
        $this->imageResource = $imageResource;
        $this->config = $config;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param int $imageId
     *
     * @return Image
     */
    public function mustLoadById(int $imageId): Image
    {
        return $this->imageResource->where('id', $imageId)->limit(1)->first();
    }

    /**
     * @param UploadedFile $upload
     *
     * @return Image
     */
    public function storeUploadedImage(UploadedFile $upload): Image
    {
        $newImage = $this->imageResource->create(
            [
            'filename' => uniqid().$upload->getClientOriginalName(),
            ]
        );
        $upload->move(storage_path('image'), $newImage->filename());

        $this->dispatcher->fire(new NewImageEvent($newImage));

        return $newImage;
    }

    /**
     * @param FileBag|UploadedFile[] $images
     * @param Product                $product
     */
    public function attachUploadedImagesToProduct($images, Product $product)
    {
        $product->attachImages(
            array_map(
                function (UploadedFile $image) {
                    return $this->storeUploadedImage($image)->id;
                },
                $images instanceof FileBag ? $images->all() : (array) $images
            )
        );
    }

    /**
     * @param Image   $image
     * @param Product $product
     *
     * @return int
     */
    public function detachImageFromProduct(Image $image, Product $product)
    {
        return $product->images()->detach($image->id);
    }

    /**
     * @param int $limit
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function loadLatest(int $limit = 1000)
    {
        return $this->imageResource
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * @param int $id
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function deleteById(int $id): bool
    {
        return (bool) $this->imageResource
            ->where('id', '=', $id)
            ->limit(1)
            ->delete();
    }

    /**
     * Transfer local images to cloud storage.
     */
    public function transferLocalImages()
    {
        $this->imageResource->orWhere(
            function (Builder $query) {
                    $query->where('filename', '!=', '');
                    $query->whereNotNull('filename');
            }
        )->get()->each(
            function (Image $image) {
                    $this->dispatcher->fire(new NewImageEvent($image));
            }
        );
    }
}
