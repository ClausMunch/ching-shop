<?php

namespace Testing\Functional\Util;

use ChingShop\Modules\Catalogue\Domain\Price\Price;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Sales\Domain\Address;
use ChingShop\Modules\Sales\Domain\Basket\Basket;
use ChingShop\Modules\Sales\Domain\Order\Order;
use Illuminate\Database\Eloquent\Model;
use Testing\Functional\Customer\CustomerUsers;
use Testing\Functional\FunctionalTest;

/**
 * Complete sequences of sales interactions as a customer.
 */
trait SalesInteractions
{
    use CustomerUsers, CreateCatalogue, MockPayPal;

    /** @var Address */
    private $address;

    /** @var Product[] */
    private $productsInBasket;

    /** @var Order[] */
    private $orders;

    /**
     * @param FunctionalTest $test
     *
     * @throws \InvalidArgumentException
     *
     * @return Order
     */
    private function completeOrder(FunctionalTest $test): Order
    {
        $this->completeCheckoutToAddress($test);
        $this->customerWillReturnFromPayPal();
        $this->press('Pay with PayPal');
        $this->see('order-id');

        $order = $this->orderFromPage($test);

        $test->assertInstanceOf(Address::class, $this->address);
        $test->assertInstanceOf(Order::class, $order);

        return $order;
    }

    /**
     * @param FunctionalTest $test
     *
     * @return Product
     */
    private function createProductAndAddToBasket(FunctionalTest $test): Product
    {
        $product = $this->createProduct();
        $product->prices()->save(
            new Price(
                [
                    'units'    => random_int(1, 99),
                    'subunits' => random_int(1, 99),
                ]
            )
        );
        $this->createProductOptionFor($product);
        $this->addProductToBasket($product, $test);

        $this->productsInBasket[] = $product;

        return $product;
    }

    /**
     * @param Product        $product
     * @param FunctionalTest $test
     */
    private function addProductToBasket(Product $product, FunctionalTest $test)
    {
        $test->actingAs($this->customerUser())
            ->visit(route('product::view', [$product->id, $product->slug]))
            ->see($product->name)
            ->press('Add to basket');
    }

    /**
     * @return Basket|Model
     */
    private function basket(): Basket
    {
        $basket = Basket::where(
            'user_id',
            '=',
            $this->customerUser()->id
        )->first();
        if ($basket) {
            return $basket->load('basketItems.productOption');
        }

        $basket = new Basket();
        $this->customerUser()->basket()->save($basket);

        return $basket;
    }

    /**
     * @param FunctionalTest $test
     *
     * @return Address
     */
    private function completeCheckoutToAddress(FunctionalTest $test): Address
    {
        $this->createProductAndAddToBasket($test);

        return $this->fillCheckoutAddress($test);
    }

    /**
     * @param FunctionalTest $test
     *
     * @return Address
     */
    private function fillCheckoutAddress(FunctionalTest $test): Address
    {
        $addressName = uniqid('address-name', false);
        $test->actingAs($this->customerUser())
            ->visit(route('sales.customer.checkout.address'))
            ->type($addressName, 'name')
            ->type('42 Some Street', 'line_one')
            ->type('Some Town', 'line_two')
            ->type('London', 'city')
            ->type('FOO BAR', 'post_code')
            ->select('GB', 'country_code')
            ->press('Continue')
            ->assertResponseOk();

        $this->address = Address::where('name', '=', $addressName)->first();

        return $this->address;
    }

    /**
     * @param FunctionalTest $test
     *
     * @return Order|null
     */
    private function orderFromPage(FunctionalTest $test): Order
    {
        $test->see('id="order-id"');

        $this->orders[] = Order::where(
            'id',
            '=',
            Order::privateId($test->getElementText('#order-id'))
        )->first();

        return end($this->orders);
    }
}
