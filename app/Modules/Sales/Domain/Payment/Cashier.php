<?php

namespace ChingShop\Modules\Sales\Domain\Payment;

use ChingShop\Modules\Catalogue\Domain\Inventory\Inventory;
use ChingShop\Modules\Sales\Domain\Basket\Basket;
use ChingShop\Modules\Sales\Domain\Basket\BasketItem;
use ChingShop\Modules\Sales\Domain\Order;
use ChingShop\Modules\Sales\Domain\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Psr\Log\LoggerInterface;

/**
 * Manages order creation by payment.
 *
 * Class Cashier
 */
class Cashier
{
    /** @var Inventory */
    private $inventory;

    /** @var LoggerInterface */
    private $log;

    /**
     * Cashier constructor.
     *
     * @param Inventory       $inventory
     * @param LoggerInterface $log
     */
    public function __construct(Inventory $inventory, LoggerInterface $log)
    {
        $this->inventory = $inventory;
        $this->log = $log;
    }

    /**
     * @param Basket     $basket
     * @param Settlement $settlement
     *
     * @throws \Exception
     *
     * @return Order
     */
    public function settle(Basket $basket, Settlement $settlement): Order
    {
        $order = $this->basketToOrder($basket);
        $payment = $this->paymentForSettlement($settlement);

        $order->payment()->save($payment);

        return $order;
    }

    /**
     * @param Settlement|Model $settlement
     *
     * @return Payment
     */
    private function paymentForSettlement(Settlement $settlement): Payment
    {
        $payment = new Payment();
        $payment->settlement()->associate($settlement);

        return $payment;
    }

    /**
     * @param Basket $basket
     *
     * @throws \Exception
     *
     * @return Order
     */
    private function basketToOrder(Basket $basket): Order
    {
        $order = Order::create();
        $basket->order()->associate($order);

        $order->orderItems()->saveMany(
            array_map(
                function (BasketItem $basketItem) {
                    return $this->basketItemToOrderItem($basketItem);
                },
                $basket->basketItems->all()
            )
        );

        $basket->delete();

        return $order;
    }

    /**
     * @param BasketItem $basketItem
     *
     * @return OrderItem
     * @throws \RuntimeException
     */
    private function basketItemToOrderItem(BasketItem $basketItem)
    {
        $orderItem = new OrderItem();
        $orderItem->basketItem()->associate($basketItem);

        $stockItem = $this->inventory->allocate(
            $basketItem->productOption
        );

        if (!$stockItem->isAvailable()) {
            throw new \RuntimeException(
                sprintf(
                    'Failed to allocate stock for product option `%s`.',
                    $basketItem->productOption->id
                )
            );
        }

        $orderItem->stockItem()->save($stockItem);

        return $orderItem;
    }
}
