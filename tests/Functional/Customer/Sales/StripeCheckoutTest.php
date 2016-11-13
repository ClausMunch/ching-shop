<?php

namespace Testing\Functional\Customer\Sales;

use ChingShop\Modules\Sales\Http\Requests\Customer\StripePaymentRequest;
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
        $this->payWithStripe();

        // Then the order should be complete.
        $this->see('order is confirmed');
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function payWithStripe()
    {
        $this->customerWillPayWithStripe();

        return $this->actingAs($this->customerUser())
            ->visit(route('sales.customer.checkout.choose-payment'))
            ->post(
                route('sales.customer.stripe.pay'),
                [
                    StripePaymentRequest::TOKEN => 'mock-token',
                    'csrf_token'                => csrf_token(),
                ]
            )
            ->followRedirects();
    }
}
