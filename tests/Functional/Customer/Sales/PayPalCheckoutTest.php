<?php

namespace Testing\Functional\Customer\Sales;

use ChingShop\Modules\Sales\Domain\Order\Order;
use Testing\Functional\FunctionalTest;
use Testing\Functional\Util\SalesInteractions;

/**
 * Class PayPalCheckoutTest.
 */
class PayPalCheckoutTest extends FunctionalTest
{
    use SalesInteractions;

    /**
     * Should be able to complete checkout with PayPal.
     *
     * @slowThreshold 1800
     */
    public function testPayPalCheckout()
    {
        // Given we are at the payment method page in the checkout process;
        $this->createProductAndAddToBasket($this);
        $this->completeCheckoutToAddress($this);

        // When we pay with PayPal;
        $this->customerWillReturnFromPayPal('approved');
        $this->press('Pay with PayPal');

        // Then our order should be confirmed.
        $this->see('order is confirmed');
    }

    /**
     * An error during PayPal checkout should be handled gracefully.
     *
     * @slowThreshold 1100
     */
    public function testErrorDuringPayPalCheckout()
    {
        // Given we are at the payment method page in the checkout process;
        $this->createProductAndAddToBasket($this);
        $this->completeCheckoutToAddress($this);

        // When we pay with PayPal and something goes wrong;
        $this->customerWillReturnFromPayPal('failed');
        $this->press('Pay with PayPal');

        // Then we should see a reassuring and useful page.
        $this->see('Something went wrong');
        $this->see('have not been charged');
    }

    /**
     * Should be able to cancel PayPal checkout.
     */
    public function testPayPalCancel()
    {
        // Given we are at the payment method page in the checkout process;
        $this->createProductAndAddToBasket($this);
        $this->completeCheckoutToAddress($this);

        // When we pay with PayPal and then cancel;
        $this->customerWillCancelPayPal();
        $this->press('Pay with PayPal');

        // Then we should see a reassuring and useful page.
        $this->see('No worries');
        $this->see('cancelled');
    }

    /**
     * The payer's email address should be stored after PayPal checkout.
     */
    public function testPayPalPayerEmail()
    {
        // Given we are at the payment method page in the checkout process;
        $this->createProductAndAddToBasket($this);
        $this->completeCheckoutToAddress($this);

        // And we have a PayPal email address;
        $emailAddress = 'test@ching-shop.com';
        $this->mockPayPalPayment()
            ->shouldReceive('getPayer')
            ->zeroOrMoreTimes()
            ->andReturnSelf();
        $this->mockPayPalPayment()
            ->shouldReceive('getPayerInfo')
            ->zeroOrMoreTimes()
            ->andReturnSelf();
        $this->mockPayPalPayment()
            ->shouldReceive('getEmail')
            ->zeroOrMoreTimes()
            ->andReturn($emailAddress);

        // When we pay with PayPal;
        $this->customerWillReturnFromPayPal('approved');
        $this->press('Pay with PayPal');

        // Then our order should be confirmed;
        $this->see('order is confirmed');

        // And the email address should be stored for the order.
        $order = Order::where(
            'id',
            '=',
            Order::privateId($this->getElementText('#order-id'))
        )->firstOrFail();

        $this->assertEquals($emailAddress, $order->payerEmail());
    }
}
