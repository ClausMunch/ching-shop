<?php

namespace ChingShop\Image\Imagick;

use ChingShop\Image\Image;
use Imagick;

/**
 * Class ImageSizeSet.
 */
class ImageSizeSet implements ImageTransformer
{
    /**
     * @param ImagickCollection $images
     */
    public function applyTo(ImagickCollection $images)
    {
        /** @var ImagickContract $image */
        foreach ($images as $image) {
            foreach (Image::SIZES as $sizeName => $size) {
                /* @noinspection DisconnectedForeachInstructionInspection */
                $sized = $image->getImage();
                $sized->scaleImage($size, 0);
                $sized->setFilename($this->sizedFilename($sizeName, $sized));
                $images->push($sized);
            }
        }
    }

    /**
     * @param $sizeName
     * @param ImagickContract|Imagick $sized
     *
     * @return string
     */
    private function sizedFilename(string $sizeName, $sized): string
    {
        $pathInfo = pathinfo($sized->getFilename());

        return "{$pathInfo['filename']}-{$sizeName}.{$pathInfo['extension']}";
    }
}
