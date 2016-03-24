<?php

namespace ChingShop\Image\Imagick;

use Imagick;
use ImagickPixel;

class WaterMark implements ImageTransformer
{
    const SCALE = 0.4;
    const MARGIN = 0.01;
    const LOCATION = 'assets/img/watermark.svg';

    /** @var ImagickContract */
    private $waterMark;

    /**
     * WaterMark constructor.
     *
     * @param ImagickContract $waterMark
     */
    public function __construct(ImagickContract $waterMark)
    {
        $this->waterMark = $waterMark;
    }

    /**
     * @param ImagickCollection $images
     */
    public function applyTo(ImagickCollection $images)
    {
        foreach ($images as $image) {
            $this->addWaterMarkToImage($image);
        }
    }

    /**
     * @param ImagickContract $image
     */
    private function addWaterMarkToImage(ImagickContract $image)
    {
        $this->waterMark()->scaleImage(
            $image->getImageWidth() * self::SCALE,
            0
        );
        $margin = $image->getImageWidth() * self::MARGIN;
        $image->compositeImage(
            $this->waterMark()->getImage(),
            Imagick::COMPOSITE_DEFAULT,
            $margin,
            array_sum([
                $image->getImageHeight(),
                -$this->waterMark()->getImageHeight(),
                -$margin,
            ])
        );
    }

    /**
     * @return ImagickContract
     */
    private function waterMark()
    {
        if (!$this->waterMark->count()) {
            $this->waterMark->setBackgroundColor(
                new ImagickPixel('transparent')
            );
            $this->waterMark->readImage(resource_path(self::LOCATION));
        }

        return $this->waterMark;
    }
}
