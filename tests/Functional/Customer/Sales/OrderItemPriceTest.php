<?php

namespace Testing\Functional\Customer\Sales;

use ChingShop\Modules\Catalogue\Domain\Price\Price;
use ChingShop\Modules\Sales\Domain\Order\OrderItem;
use Testing\Functional\FunctionalTest;
use Testing\Functional\Util\SalesInteractions;

/**
 * Test consistency of order item prices.
 */
class OrderItemPriceTest extends FunctionalTest
{
    use SalesInteractions;

    /**
     * An order item should have a consistent price.
     */
    public function testOrderItemPriceIsConsistent()
    {
        // When I complete an order;
        /** @var OrderItem $item */
        $item = $this->completeOrder($this)->orderItems->first();
        $originalPrice = $item->priceAsFloat();

        // And the price of a product in the order subsequently changes;
        /** @var Price $price */
        $price = $item->basketItem->productOption->product->prices->first();
        $price->units *= 2;
        $price->save();

        // Then the price of my order item should be unchanged.
        $item->fresh();
        $this->assertEquals($originalPrice, $item->priceAsFloat());
    }
}
