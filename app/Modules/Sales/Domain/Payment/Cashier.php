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
 * @package ChingShop\Modules\Sales\Domain\Payment
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
     * @return Order
     * @throws \Exception
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
     * @return Order
     * @throws \Exception
     */
    private function basketToOrder(Basket $basket): Order
    {
        $order = Order::create();
        $basket->order()->associate($order);

        $order->orderItems()->saveMany(
            array_map(
                function (BasketItem $basketItem) {
                    $orderItem = new OrderItem();
                    $orderItem->basketItem()->associate($basketItem);

                    $orderItem->stockItem()->save(
                        $this->inventory->allocate(
                            $basketItem->productOption
                        )
                    );

                    return $orderItem;
                },
                $basket->basketItems->all()
            )
        );

        $basket->delete();

        return $order;
    }
}
