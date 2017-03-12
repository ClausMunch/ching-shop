<?php

namespace Testing\Functional\Customer\Sales;

use ChingShop\Modules\Sales\Domain\Order\Order;
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

    /**
     * Payer email should be persisted after Stripe checkout.
     */
    public function testStripePayerEmail()
    {
        // Given we have completed checkout up to the address;
        $this->completeCheckoutToAddress($this);

        // And we have a enter an email address in the Stripe checkout;
        $emailAddress = 'test@ching-shop.com';
        /** @noinspection PhpUndefinedFieldInspection */
        $this->mockStripeCharge()->source = (object) ['name' => $emailAddress];

        // When we pay with Stripe;
        $this->payWithStripe($this, $this->customerUser());

        // Then the order should be complete;
        $this->see('order is confirmed');

        // And the payer email should be stored for the order.
        $order = Order::where(
            'id',
            '=',
            Order::privateId($this->getElementText('#order-id'))
        )->firstOrFail();

        $this->assertEquals($emailAddress, $order->payerEmail());
    }
}
