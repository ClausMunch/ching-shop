<?php

namespace ChingShop\Image;

use ChingShop\Events\NewImageEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Events\Dispatcher;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ImageRepository.
 */
class ImageRepository
{
    /** @var Image|Builder */
    private $imageResource;

    /** @var Dispatcher */
    private $dispatcher;

    /**
     * ImageRepository constructor.
     *
     * @param Image      $imageResource
     * @param Dispatcher $dispatcher
     */
    public function __construct(
        Image $imageResource,
        Dispatcher $dispatcher
    ) {
        $this->imageResource = $imageResource;
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
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     *
     * @return Image
     */
    public function storeUploadedImage(UploadedFile $upload): Image
    {
        $newImage = $this->imageResource->create(
            ['filename' => uniqid('', true).$upload->getClientOriginalName()]
        );
        $upload->move(storage_path('image'), $newImage->filename());

        $this->dispatcher->fire(new NewImageEvent($newImage));

        return $newImage;
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function loadLatest()
    {
        return $this->imageResource->orderBy('updated_at', 'desc')->paginate();
    }

    /**
     * @param int $imageId
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function deleteById(int $imageId): bool
    {
        return (bool) $this->imageResource
            ->where('id', '=', $imageId)
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
