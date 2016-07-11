<?php

namespace ChingShop\Image\Imagick;

/**
 * Interface ImageTransformer.
 */
interface ImageTransformer
{
    /**
     * @param ImagickCollection $images
     */
    public function applyTo(ImagickCollection $images);
}
