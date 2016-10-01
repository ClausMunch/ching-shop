<?php

namespace ChingShop\Modules\Sales\Domain\Payment;

use ChingShop\Modules\Catalogue\Domain\Inventory\Inventory;
use ChingShop\Modules\Sales\Domain\Basket\Basket;
use ChingShop\Modules\Sales\Domain\Basket\BasketItem;
use ChingShop\Modules\Sales\Domain\Order\Order;
use ChingShop\Modules\Sales\Domain\Order\OrderItem;
use ChingShop\Modules\Sales\Events\NewOrderEvent;
use Illuminate\Contracts\Events\Dispatcher;
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

    /** @var Dispatcher */
    private $dispatcher;

    /** @var LoggerInterface */
    private $log;

    /**
     * Cashier constructor.
     *
     * @param Inventory       $inventory
     * @param LoggerInterface $log
     * @param Dispatcher      $dispatcher
     */
    public function __construct(
        Inventory $inventory,
        LoggerInterface $log,
        Dispatcher $dispatcher
    ) {
        $this->inventory = $inventory;
        $this->dispatcher = $dispatcher;
        $this->log = $log;
    }

    /**
     * @param Basket     $basket
     * @param Settlement $settlement
     *
     * @throws \Exception
     *
     * @return \ChingShop\Modules\Sales\Domain\Order\Order
     */
    public function settle(Basket $basket, Settlement $settlement): Order
    {
        $order = $this->basketToOrder($basket);
        $payment = $this->paymentForSettlement($settlement);

        $order->payment()->save($payment);

        $this->dispatcher->fire(new NewOrderEvent($order));

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
     * @return \ChingShop\Modules\Sales\Domain\Order\Order
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

        $order->address()->associate($basket->address);
        $order->save();

        $basket->delete();

        return $order;
    }

    /**
     * @param BasketItem $basketItem
     *
     * @throws \RuntimeException
     *
     * @return \ChingShop\Modules\Sales\Domain\Order\OrderItem
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
