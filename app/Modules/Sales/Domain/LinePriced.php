<?php

namespace ChingShop\Modules\Sales\Domain;

/**
 * Interface LinePriced
 *
 * @package ChingShop\Modules\Sales\Domain
 */
interface LinePriced
{
    /**
     * @return Money
     */
    public function linePrice(): Money;
}
