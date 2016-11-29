<?php

namespace ChingShop\Domain;

/**
 * Colour value object
 */
class Colour
{
    const BRAND = '#ef4560';

    /** @var int */
    private $red;

    /** @var int */
    private $green;

    /** @var int */
    private $blue;

    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     */
    public function __construct(int $red, int $green, int $blue)
    {
        $this->red = $this->eightBit($red);
        $this->green = $this->eightBit($green);
        $this->blue = $this->eightBit($blue);
    }

    /**
     * @return int
     */
    public function red(): int
    {
        return $this->red;
    }

    /**
     * @param int $red
     */
    public function setRed(int $red)
    {
        $this->red = $this->eightBit($red);
    }

    /**
     * @return int
     */
    public function green(): int
    {
        return $this->green;
    }

    /**
     * @param int $green
     */
    public function setGreen(int $green)
    {
        $this->green = $this->eightBit($green);
    }

    /**
     * @return int
     */
    public function blue(): int
    {
        return $this->blue;
    }

    /**
     * @param int $blue
     */
    public function setBlue(int $blue)
    {
        $this->blue = $this->eightBit($blue);
    }

    /**
     * @param string $hex
     *
     * @return Colour
     */
    public static function fromHex(string $hex): Colour
    {
        $hex = str_replace_first('#', '', $hex);

        return new self(
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        );
    }

    /**
     * @return Colour
     */
    public static function brand(): Colour
    {
        return self::fromHex(self::BRAND);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "#{$this->toHex()}";
    }

    /**
     * @return string
     */
    public function toHex(): string
    {
        return sprintf('%02x%02x%02x', $this->red, $this->green, $this->blue);
    }

    /**
     * @return Colour
     */
    public function pastel(): Colour
    {
        return $this->mix(new Colour(255, 200, 255));
    }

    /**
     * @param Colour $colour
     *
     * @return Colour
     */
    public function mix(Colour $colour): Colour
    {
        return new self(
            ($this->red + $colour->red()) / 2,
            ($this->green + $colour->green()) / 2,
            ($this->blue + $colour->blue()) / 2
        );
    }

    /**
     * @param int $number
     *
     * @return int
     */
    private function eightBit(int $number): int
    {
        return min(255, max(0, $number));
    }
}
