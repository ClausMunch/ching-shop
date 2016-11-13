<?php

namespace ChingShop\Modules\Sales\Domain\Payment;

use ChingShop\Modules\Catalogue\Domain\Inventory\Inventory;
use ChingShop\Modules\Catalogue\Domain\Product\ProductOptionPresenter;
use ChingShop\Modules\Sales\Domain\Basket\Basket;
use ChingShop\Modules\Sales\Domain\Basket\BasketItem;
use ChingShop\Modules\Sales\Domain\Basket\BasketItemPresenter;
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

        foreach ($basket->basketItems as $basketItem) {
            if ($basketItem instanceof BasketItemPresenter) {
                $basketItem = $basketItem->getWrappedObject();
            }
            $this->basketItemToOrderItem($basketItem, $order);
        }

        $order->address()->associate($basket->address);
        $order->save();

        $basket->delete();

        return $order;
    }

    /**
     * @param BasketItem $basketItem
     * @param Order      $order
     *
     * @throws \RuntimeException
     */
    private function basketItemToOrderItem(BasketItem $basketItem, Order $order)
    {
        $orderItem = new OrderItem();
        $orderItem->price = $basketItem->priceAsFloat();
        $orderItem->basketItem()->associate($basketItem);

        $productOption = $basketItem->productOption;
        if ($productOption instanceof ProductOptionPresenter) {
            $productOption = $productOption->getWrappedObject();
        }
        $stockItem = $this->inventory->allocate($productOption);

        if (!$stockItem->isAvailable()) {
            throw new StockAllocationException(
                sprintf(
                    'Failed to allocate stock for product option `%s`.',
                    $basketItem->productOption->id
                )
            );
        }

        $order->orderItems()->save($orderItem);
        $orderItem->stockItem()->save($stockItem);
    }
}
