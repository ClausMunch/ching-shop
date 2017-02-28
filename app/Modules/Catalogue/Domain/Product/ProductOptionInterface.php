<?php

namespace ChingShop\Modules\Catalogue\Domain\Product;

/**
 * Interface ProductOptionInterface.
 */
interface ProductOptionInterface
{
    /**
     * @return bool
     */
    public function isInStock(): bool;
}
