<?php

namespace Testing\Functional\Sales;

use Testing\Functional\FunctionalTest;

class PayPalCheckoutTest extends FunctionalTest
{
    use SalesInteractions;

    /**
     * Should be able to pay with PayPal.
     */
    public function testPayPalCheckout()
    {
        // Given we've got to the choose payment page;
        $this->completeCheckoutAddress($this);

        // When we select PayPal as the payment method;
        $this->press('Pay with PayPal');

        self::markTestIncomplete();
    }
}
