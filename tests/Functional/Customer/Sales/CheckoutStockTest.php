<?php

namespace Testing\Functional\Customer\Sales;

use ChingShop\Modules\Catalogue\Domain\Inventory\StockItem;
use ChingShop\Modules\Catalogue\Domain\Product\ProductOption;
use ChingShop\Modules\Sales\Domain\Basket\BasketItem;
use Testing\Functional\FunctionalTest;
use Testing\Functional\Staff\StaffUser;
use Testing\Functional\Util\SalesInteractions;

/**
 * Available stock should be reduced on purchase.
 */
class CheckoutStockTest extends FunctionalTest
{
    use SalesInteractions, StaffUser;

    /**
     * Purchasing a product should reduce the stock of the purchased option.
     */
    public function testPurchaseReducesStock()
    {
        // Given there is a product option with 6 stock items;
        $product = $this->createProductAndAddToBasket($this);
        $option = $product->options->first();
        for ($i = 0; $i < 5; $i++) {
            $option->stockItems()->save(new StockItem());
        }

        // When we purchase that product option;
        $this->completeCheckoutToAddress($this);
        $this->customerWillReturnFromPayPal('approved');
        $this->press('Pay with PayPal');

        // Then the stock for that option should be reduced by 1.
        $this->actingAs($this->staffUser())
            ->visit(route('products.show', [$product->sku]));
        $this->assertEquals(
            5,
            $this->getElementAttribute("#option-{$option->id}-stock", 'value')
        );
    }

    /**
     * It should not be possible to add more stock than is available to the
     * basket.
     */
    public function testCantAddMoreStockToBasketThanIsAvailable()
    {
        // Given there is a product option with 2 stock items;
        $product = $this->createProductAndAddToBasket($this);
        /** @var ProductOption $option */
        $option = $product->options->first();
        $option->stockItems()->save(new StockItem());

        // When we try to add it to the basket 2 more times;
        $this->addProductToBasket($product, $this);
        $this->see($option->label);
        $this->see('added to your basket');
        $this->addProductToBasket($product, $this);

        // Then we should get a message explaining why we can't add more;
        $this->see('are available');

        // And there should be 2 in the basket.
        $this->assertCount(2, $this->basket()->itemsForOption($option));
    }

    /**
     * It should not be possible to buy more stock than is available.
     *
     * @slowThreshold 2000
     */
    public function testCantBuyMoreStockThanAvailable()
    {
        // Given there is a product option with 2 stock items;
        $product = $this->createProductAndAddToBasket($this);
        /** @var ProductOption $option */
        $option = $product->options->first();
        $option->stockItems()->save(new StockItem());

        // And somehow we have it in the basket three times;
        for ($i = 0; $i < 3; $i++) {
            factory(BasketItem::class)->times(3)->create(
                [
                    'product_option_id' => $option->id,
                    'basket_id'         => $this->basket()->id,
                ]
            );
        }

        // Then we should not be able to make payment;
        $this->customerWillGoToPayPal();
        $this->mockPayPalPayment()
            ->shouldReceive('get')
            ->zeroOrMoreTimes()
            ->andReturnSelf();
        $this->mockPayPalPayment()->shouldReceive('getState')->never();

        // When we try to complete checkout.
        $this->completeCheckoutToAddress($this);
        $this->press('Pay with PayPal');
        $this->see('not able to allocate stock');
    }
}
