<?php

namespace ChingShop\Modules\Catalogue\Domain\Product;

/**
 * Interface ProductOptionInterface
 *
 * @package ChingShop\Modules\Catalogue\Domain\Product
 */
interface ProductOptionInterface
{
    /**
     * @return bool
     */
    public function isInStock(): bool;
}
