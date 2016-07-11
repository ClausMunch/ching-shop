<?php

namespace ChingShop\Image\Imagick;

use ChingShop\Image\Image;

/**
 * Class ImagePreProcessor.
 */
class ImagePreProcessor
{
    /** @var ImagickContract */
    private $imagick;

    /** @var ImageTransformer[] */
    private $transformers;

    /**
     * ImagePreProcessor constructor.
     *
     * @param ImagickContract $imagick
     */
    public function __construct(ImagickContract $imagick)
    {
        $this->imagick = $imagick;
    }

    /**
     * @param ImageTransformer $imageTransformer
     */
    public function addTransformer(ImageTransformer $imageTransformer)
    {
        $this->transformers[] = $imageTransformer;
    }

    /**
     * @param Image $image
     *
     * @return ImagickCollection
     */
    public function preProcess(Image $image): ImagickCollection
    {
        $this->imagick->readImage($image->storageLocation());
        $this->imagick->setFilename($image->filename());

        $collection = new ImagickCollection([$this->imagick]);
        foreach ($this->transformers as $transformer) {
            $transformer->applyTo($collection);
        }

        return $collection;
    }
}
