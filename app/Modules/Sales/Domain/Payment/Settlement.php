<?php

namespace ChingShop\Modules\Sales\Domain\Payment;

/**
 * Interface Settlement.
 */
interface Settlement
{
    /**
     * @return string
     */
    public function type(): string;
}
