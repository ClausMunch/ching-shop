<?php

namespace ChingShop\Domain;

use App;
use Jenssegers\Optimus\Optimus;
use Propaganistas\LaravelFakeId\FakeIdTrait;

/**
 * Encode and decode optimus ids for public display.
 */
trait PublicId
{
    use FakeIdTrait;

    /** @var Optimus */
    private static $optimus;

    /**
     * @return int
     */
    public function publicId(): int
    {
        return (int) $this->getRouteKey();
    }

    /**
     * @param int $publicId
     *
     * @return int
     */
    public static function privateId(int $publicId): int
    {
        return self::optimus()->decode($publicId);
    }

    /**
     * @return Optimus
     */
    private static function optimus(): Optimus
    {
        if (self::$optimus === null) {
            self::$optimus = App::make(Optimus::class);
        }

        return self::$optimus;
    }
}
