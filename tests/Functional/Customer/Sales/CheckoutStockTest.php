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
        $option = $product->options[0];
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
}
