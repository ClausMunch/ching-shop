<?php

namespace Testing\Functional\Customer\Sales;

use ChingShop\Modules\Catalogue\Domain\Inventory\StockItem;
use ChingShop\Modules\Sales\Domain\Offer\Offer;
use Testing\Functional\FunctionalTest;
use Testing\Functional\Util\SalesInteractions;

/**
 * Test offer display for basket functionality.
 */
class BasketOffersTest extends FunctionalTest
{
    use SalesInteractions;

    /**
     * An offer with a fixed price should be applied to the basket when the
     * minimum quantity is met.
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
        $this->addProductToBasket($product, $this);
        $this->addProductToBasket($product, $this);
        $this->addProductToBasket($product, $this);

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
}
