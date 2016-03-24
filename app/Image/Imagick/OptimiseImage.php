<?php

namespace ChingShop\Image\Imagick;

use Imagick;

class OptimiseImage implements ImageTransformer
{
    /**
     * @param ImagickCollection $images
     */
    public function applyTo(ImagickCollection $images)
    {
        /** @var ImagickContract $image */
        foreach($images as $image) {
            $image->setInterlaceScheme(Imagick::INTERLACE_PLANE);
            $image->setCompression(Imagick::COMPRESSION_JPEG);
            $image->setCompressionQuality(85);
        }
    }
}
