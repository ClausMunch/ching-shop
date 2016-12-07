<?php

namespace Testing\Functional\Customer\Sales\Offers;

use ChingShop\Modules\Catalogue\Domain\Inventory\StockItem;
use ChingShop\Modules\Sales\Domain\Offer\Offer;
use Testing\Functional\FunctionalTest;
use Testing\Functional\Util\MockStripe;
use Testing\Functional\Util\SalesInteractions;
use Testing\TestUtil;

/**
 * Test order offer application functionality.
 */
class OrderOffersTest extends FunctionalTest
{
    use SalesInteractions, MockStripe, TestUtil;

    /**
     * Should be able to make an order with an offer applied.
     */
    public function testOfferAppliedToOrder()
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

        // When we complete checkout with enough applicable products;
        $this->repeat(
            3,
            function () use ($product) {
                $this->addProductToBasket($product, $this);
            }
        );
        $this->fillCheckoutAddress($this);
        $this->payWithStripe($this, $this->customerUser());

        // Then the offer should have been applied.
        $order = $this->orderFromPage($this);
        $this->assertCount(3, $order->orderItems);
        $this->assertEquals(1000, $order->totalPrice()->amount());
        $this->assertEquals('£10.00', $order->totalPrice()->formatted());
        $this->assertEquals(
            $offer->id,
            $order->orderOffers->first()->offer->id
        );
    }
}
