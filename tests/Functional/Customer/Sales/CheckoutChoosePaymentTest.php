<?php

namespace Testing\Functional\Customer\Sales;

use Testing\Functional\FunctionalTest;
use Testing\Functional\Util\SalesInteractions;

/**
 * Class CheckoutChoosePaymentTest.
 *
 * @group sales
 * @group checkout
 */
class CheckoutChoosePaymentTest extends FunctionalTest
{
    use SalesInteractions;

    /**
     * Should be able to go back to the address form from the choose payment
     * method page.
     */
    public function testCanGoBackToAddress()
    {
        // Given we've filled in the address;
        $address = $this->completeCheckoutToAddress($this);

        // And we're on the choose payment method page;
        $this->seePageIs(route('sales.customer.checkout.choose-payment'));

        // Then we should be able to go back to the address form.
        $this->click('Back to address')
            ->see($address->name)
            ->see($address->line_one)
            ->see($address->post_code)
            ->see($address->country);
    }

    /**
     * Should be able to go to the pay by card page from the choose payment
     * method page.
     */
    public function testCanGoToPayByCard()
    {
        self::markTestIncomplete('Card payment is not yet implemented');
    }
}
