<?php

namespace ChingShop\Modules\Sales\Domain\Order;

use Analytics;

/**
 * Track orders in analytics.
 */
trait TracksOrders
{
    /**
     * @param Order $order
     *
     * @throws \InvalidArgumentException
     */
    private function trackOrder(Order $order)
    {
        Analytics::enableEcommerceTracking();
        Analytics::ecommerceAddTransaction(
            $order->publicId(),
            config('app.name'),
            $order->totalPrice()->asFloat(),
            0.00,
            0.00
        );
        $order->orderItems->each(
            function (OrderItem $item) {
                Analytics::ecommerceAddItem(
                    $item->id,
                    $item->name(),
                    $item->sku(),
                    $item->category()->name,
                    $item->priceAsFloat(),
                    1
                );
            }
        );
    }
}
