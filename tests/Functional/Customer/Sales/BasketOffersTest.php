<?php

namespace Testing\Functional\Customer\Sales;

use ChingShop\Modules\Catalogue\Domain\Inventory\StockItem;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Sales\Domain\Offer\Offer;
use Testing\Functional\FunctionalTest;
use Testing\Functional\Util\SalesInteractions;
use Testing\TestUtil;

/**
 * Test offer display for basket functionality.
 */
class BasketOffersTest extends FunctionalTest
{
    use SalesInteractions, TestUtil;

    /**
     * An offer with a fixed price should be applied to the basket when the
     * minimum quantity is met.
     *
     * @slowThreshold 1500
     */
    public function testDisplayAbsolutePriceOfferOnMinimumQuantity()
    {
        // Given there is an offer with a minimum quantity of 3 and a fixed
        // price of £10;
        /** @var Offer $offer */
        $offer = factory(Offer::class)->states('price')->create(
            [
                'quantity' => 3,
                'effect'   => Offer::ABSOLUTE,
                'price'    => 1000,
            ]
        );
        $product = $this->createProductWithPrice(3, 50);
        $product->options->first()->stockItems()->saveMany(
            factory(StockItem::class)->times(5)->make()
        );
        $offer->products()->save($product);

        // When we have enough applicable products in the basket;
        $this->repeat(
            3,
            function () use ($product) {
                $this->addProductToBasket($product, $this);
            }
        );

        // Then the discount should be applied;
        $this->actingAs($this->customerUser())
            ->visit(route('sales.customer.basket'));
        $this->see('-£0.50');
        $this->assertEquals(
            '£10.00',
            $this->getElementText('.basket-total-amount')
        );

        // And we should see the offer name.
        $this->see($offer->name);
    }

    /**
     * The amount of the saving should be shown in the basket.
     *
     * @slowThreshold 1100
     */
    public function testSavingShown()
    {
        // Given there is an offer with a minimum quantity of 2 and a fixed
        // price of £5;
        /** @var Offer $offer */
        $offer = factory(Offer::class)->states('price')->create(
            [
                'quantity' => 2,
                'effect'   => Offer::ABSOLUTE,
                'price'    => 500,
            ]
        );
        $product = $this->createProductWithPrice(3, 00);
        $product->options->first()->stockItems()->saveMany(
            factory(StockItem::class)->times(3)->make()
        );
        $offer->products()->save($product);

        // When we have two of the product in the basket;
        $this->addProductToBasket($product, $this);
        $this->addProductToBasket($product, $this);

        // Then the offer should be applied;
        $this->actingAs($this->customerUser())
            ->visit(route('sales.customer.basket'));
        $this->see($offer->name);
        $this->assertEquals(
            '£5.00',
            $this->getElementText('.basket-total-amount')
        );

        // And we should see the amount we've saved.
        $this->see('saving you £1.00');
    }

    /**
     * Should be able to have the same offer repeated in the basket.
     *
     * @slowThreshold 1200
     */
    public function testRepeatedOffersInBasket()
    {
        // Given there is an offer with a minimum quantity of 2;
        /** @var Offer $offer */
        $offer = factory(Offer::class)->create(
            ['quantity' => 2, 'price' => 600, 'effect' => Offer::ABSOLUTE]
        );
        $product = $this->createProduct();
        $this->createProductOptionFor($product);
        $product->options->first()->stockItems()->saveMany(
            factory(StockItem::class)->times(6)->make()
        );
        $offer->products()->save($product);

        // When we have 6 applicable products in the basket;
        $this->repeat(
            6,
            function () use ($product) {
                $this->addProductToBasket($product, $this);
            }
        );

        // Then the offer should be applied in the basket 3 times.
        $this->actingAs($this->customerUser())
            ->visit(route('sales.customer.basket'));
        $this->see($offer->name);
        $this->assertEquals(3, $this->countOnPage("discount-{$offer->id}"));
        $this->assertEquals(
            '£18.00',
            $this->getElementText('.basket-total-amount')
        );
    }

    /**
     * Should be able to get a discount with mixed products.
     *
     * @slowThreshold 900
     */
    public function testMixedProductsDiscountInBasket()
    {
        // Given there is an offer with a minimum quantity of 3;
        /** @var Offer $offer */
        $offer = factory(Offer::class)->create(
            ['quantity' => 3, 'price' => 900, 'effect' => Offer::ABSOLUTE]
        );
        $products = $this->repeat(
            3,
            function () {
                return $this->createProductWithPrice(3, 50);
            }
        );
        $products->each(
            function (Product $product) {
                $product->options->first()->stockItems()->save(
                    factory(StockItem::class)->make()
                );
            }
        );
        $offer->products()->saveMany($products);

        // When we have 3 applicable products in the basket;
        $products->each(
            function (Product $product) {
                $this->addProductToBasket($product, $this);
            }
        );

        // Then the offer should be applied.
        $this->actingAs($this->customerUser())
            ->visit(route('sales.customer.basket'));
        $this->see($offer->name);
        $this->assertEquals(
            '£9.00',
            $this->getElementText('.basket-total-amount')
        );
    }
}
