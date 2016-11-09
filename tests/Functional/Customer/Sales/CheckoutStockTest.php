<?php

namespace Testing\Functional\Customer\Sales;

use ChingShop\Modules\Catalogue\Domain\Inventory\StockItem;
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
     * It should not be possible to buy more stock than is available.
     *
     * @slowThreshold 2000
     */
    public function testCantBuyMoreStockThanAvailable()
    {
        // Given there is a product option with 2 stock items;
        $product = $this->createProductAndAddToBasket($this);
        $option = $product->options->first();
        $option->stockItems()->save(new StockItem());

        // And we add it to the basket 3 times;
        for ($i = 0; $i < 3; $i++) {
            $this->addProductToBasket($product, $this);
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
