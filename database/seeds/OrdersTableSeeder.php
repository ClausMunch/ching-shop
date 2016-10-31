<?php

use ChingShop\Modules\Catalogue\Domain\Inventory\StockItem;
use ChingShop\Modules\Catalogue\Domain\Product\ProductOption;
use ChingShop\Modules\Sales\Domain\Address;
use ChingShop\Modules\Sales\Domain\Basket\Basket;
use ChingShop\Modules\Sales\Domain\Basket\BasketItem;
use ChingShop\Modules\Sales\Domain\Order\Order;
use ChingShop\Modules\Sales\Domain\Order\OrderItem;
use ChingShop\Modules\Sales\Domain\Payment\Payment;
use ChingShop\Modules\Sales\Domain\PayPal\PayPalSettlement;

/**
 * Generate test orders and related data.
 */
class OrdersTableSeeder extends Seed
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->repeat(
            function () {
                $this->seedOrder();
            },
            5
        );
    }

    /**
     * Seed an order.
     */
    private function seedOrder()
    {
        $basket = Basket::create();

        $address = Address::create(
            [
                'name'         => $this->faker()->name,
                'line_one'     => $this->faker()->streetAddress,
                'line_two'     => $this->faker()->streetName,
                'city'         => $this->faker()->city,
                'post_code'    => $this->faker()->postcode,
                'country_code' => $this->faker()->countryCode,
            ]
        );
        $basket->address()->associate($address);
        $basket->save();

        $basketItems = [];
        $this->repeat(
            function () use ($basket, &$basketItems) {
                $basketItems[] = BasketItem::create(
                    [
                        'basket_id'         => $basket->id,
                        'product_option_id' => ProductOption::inRandomOrder()
                            ->first()->id,
                    ]
                );
            },
            random_int(1, 4)
        );

        $order = Order::create();
        $order->address()->associate($address);
        $order->save();

        /** @var BasketItem $basketItem */
        foreach ($basketItems as $basketItem) {
            $stockItem = new StockItem();
            $basketItem->productOption->stockItems()->save($stockItem);

            $orderItem = OrderItem::create(
                [
                    'basket_item_id' => $basketItem->id,
                    'order_id'       => $order->id,
                    'price'          => $basketItem->priceAsFloat(),
                ]
            );
            $orderItem->stockItem()->save($stockItem);
        }

        Payment::create(
            [
                'order_id'        => $order->id,
                'settlement_id'   => PayPalSettlement::create(
                    [
                        'payment_id' => $this->faker()->uuid,
                        'payer_id'   => $this->faker()->uuid,
                    ]
                )->id,
                'settlement_type' => PayPalSettlement::class,
            ]
        );
    }
}
