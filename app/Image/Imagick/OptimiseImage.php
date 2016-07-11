<?php

namespace ChingShop\Image\Imagick;

use Imagick;

/**
 * Class OptimiseImage.
 */
class OptimiseImage implements ImageTransformer
{
    /**
     * @param ImagickCollection $images
     */
    public function applyTo(ImagickCollection $images)
    {
        /** @var ImagickContract $image */
        foreach ($images as $image) {
            $image->stripImage();

            $image->setFormat('jpg');
            $image->setImageFormat('jpg');

            $image->gaussianBlurImage(0.5, 0.2);

            $image->setInterlaceScheme(Imagick::INTERLACE_PLANE);

            $image->setCompression(Imagick::COMPRESSION_JPEG);
            $image->setImageCompression(Imagick::COMPRESSION_JPEG);

            $image->setCompressionQuality(85);
            $image->setImageCompressionQuality(85);
        }
    }
}
