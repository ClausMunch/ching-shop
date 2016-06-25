<?php

namespace ChingShop\Image;

use ChingShop\Events\NewImageEvent;
use Illuminate\Config\Repository as Config;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Events\Dispatcher;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
    public function loadById(int $imageId): Image
    {
        return $this->imageResource->where('id', $imageId)->first();
    }

    /**
     * @param UploadedFile $upload
     *
     * @return Image
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function storeUploadedImage(UploadedFile $upload): Image
    {
        $newImage = $this->imageResource->create(
            [
                'filename' => uniqid('', true).$upload->getClientOriginalName(),
            ]
        );
        $upload->move(storage_path('image'), $newImage->filename());

        $this->dispatcher->fire(new NewImageEvent($newImage));

        return $newImage;
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
        $this->imageResource
            ->orWhere(
                function (Builder $query) {
                    $query->where('filename', '!=', '');
                    $query->whereNotNull('filename');
                }
            )
            ->get()
            ->each(
                function (Image $image) {
                    $this->dispatcher->fire(new NewImageEvent($image));
                }
            );
    }
}
