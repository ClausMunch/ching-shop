<?php

namespace Testing\Functional\Customer\Sales;

use Testing\Functional\FunctionalTest;
use Testing\Functional\Util\MockStripe;
use Testing\Functional\Util\SalesInteractions;

/**
 * Test Stripe checkout functionality.
 */
class StripeCheckoutTest extends FunctionalTest
{
    use SalesInteractions, MockStripe;

    /**
     * Should be able to checkout with Stripe.
     */
    public function testStripeCheckout()
    {
        // Given we have completed checkout up to the address;
        $this->completeCheckoutToAddress($this);

        // When we pay with Stripe;
        $this->payWithStripe($this, $this->customerUser());

        // Then the order should be complete.
        $this->see('order is confirmed');
    }
}
