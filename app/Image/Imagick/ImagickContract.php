<?php

namespace ChingShop\Image\Imagick;

use Imagick;
use ImagickPixel;

/**
 * Interface ImagickContract
 * Allows mocking Imagick in tests.
 */
interface ImagickContract
{
    /**
     * @param int  $cols
     * @param int  $rows
     * @param null $bestfit
     * @param null $legacy
     *
     * @return bool
     */
    public function scaleImage($cols, $rows, $bestfit = null, $legacy = null);

    /**
     * @return Imagick
     */
    public function getImage();

    /**
     * @return int
     */
    public function getImageHeight();

    /**
     * @return int
     */
    public function getImageWidth();

    /**
     * @return bool
     */
    public function hasNextImage();

    /**
     * @param ImagickPixel $color
     *
     * @return bool
     */
    public function setBackgroundColor($color);

    /**
     * @param string $filename
     *
     * @return bool
     */
    public function readImage($filename);

    /**
     * @param string $filename
     *
     * @return bool
     */
    public function setFilename($filename);

    /**
     * @return string
     */
    public function getFilename();

    /**
     * @param int $interlaceScheme
     *
     * @return bool
     */
    public function setInterlaceScheme($interlaceScheme);

    /**
     * @param int $compression
     *
     * @return bool
     */
    public function setCompression($compression);

    /**
     * @param int $compression
     *
     * @return bool
     */
    public function setImageCompression($compression);

    /**
     * @param int $compressionQuality
     *
     * @return bool
     */
    public function setCompressionQuality($compressionQuality);

    /**
     * @param int $quality
     *
     * @return bool
     */
    public function setImageCompressionQuality($quality);

    /**
     * @return int
     */
    public function count();

    /**
     * @param Imagick $composite_object
     * @param int     $composite
     * @param int     $x
     * @param int     $y
     * @param int     $channel
     *
     * @return mixed
     */
    public function compositeImage(
        Imagick $composite_object,
        $composite,
        $x,
        $y,
        $channel = Imagick::CHANNEL_ALL
    );

    /**
     * @return string
     */
    public function getImageBlob();

    /**
     * @return bool
     */
    public function stripImage();

    /**
     * @param $string
     *
     * @return bool
     */
    public function setFormat($string);

    /**
     * @param $string
     *
     * @return bool
     */
    public function setImageFormat($string);

    /**
     * @param float $radius
     * @param float $sigma
     * @param int   $channel
     *
     * @return bool
     */
    public function gaussianBlurImage(
        $radius,
        $sigma,
        $channel = Imagick::CHANNEL_ALL
    );
}
