<?php

namespace ChingShop\Modules\Sales\Domain\Offer;

use ChingShop\Modules\Catalogue\Domain\Product\Product;

/**
 * Something that can be included in an offer, e.g. a basket item or order item.
 */
interface OfferComponent
{
    /**
     * @return int
     */
    public function id(): int;

    /**
     * @return Product
     */
    public function product(): Product;
}
