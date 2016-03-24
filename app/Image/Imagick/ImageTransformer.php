<?php

namespace ChingShop\Image\Imagick;

interface ImageTransformer
{
    /**
     * @param ImagickCollection $images
     */
    public function applyTo(ImagickCollection $images);
}
