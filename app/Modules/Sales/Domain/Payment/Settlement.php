<?php

namespace ChingShop\Modules\Sales\Domain\Payment;

use League\Uri\Schemes\Http;

/**
 * Interface Settlement.
 */
interface Settlement
{
    /**
     * @return string
     */
    public function type(): string;

    /**
     * @return string
     */
    public function id(): string;

    /**
     * Get a URL for information about this settlement.
     *
     * @return Http
     */
    public function url(): Http;
}
