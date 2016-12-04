<?php

namespace ChingShop\Modules\Sales\Domain;

/**
 * Interface LinePriced.
 */
interface LinePriced
{
    /**
     * @return Money
     */
    public function linePrice(): Money;
}
