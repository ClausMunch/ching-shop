<?php

namespace Testing\Functional\Util;

use ChingShop\Modules\Sales\Domain\PayPal\PayPalCheckout;
use Mockery;
use Mockery\MockInterface;
use PayPal\Api\Payment;

trait MockPayPal
{
    /** @var Payment|MockInterface */
    private $payPalPayment;

    /**
     * @param string $status
     *
     * @throws \InvalidArgumentException
     */
    private function customerWillReturnFromPayPal(string $status = 'approved')
    {
        $this->mockPayPalPayment()
            ->shouldReceive('getApprovalLink')
            ->zeroOrMoreTimes()
            ->andReturn(
                route(
                    PayPalCheckout::RETURN_ROUTE,
                    [
                        'token'     => uniqid('paypal-token', false),
                        'paymentId' => $this->mockPayPalPaymentId(),
                        'payerID'   => uniqid('paypal-payer', false),
                    ]
                )
            );

        $this->mockPayPalPayment()
            ->shouldReceive('get')
            ->zeroOrMoreTimes()
            ->andReturnSelf();

        $this->mockPayPalPayment()
            ->shouldReceive('getState')
            ->zeroOrMoreTimes()
            ->andReturn($status);
    }

    /**
     * Simulate the customer going to PayPal checkout and then cancelling from
     * there.
     *
     * @throws \InvalidArgumentException
     */
    private function customerWillCancelPayPal()
    {
        $this->mockPayPalPayment()
            ->shouldReceive('getApprovalLink')
            ->zeroOrMoreTimes()
            ->andReturn(route(PayPalCheckout::CANCEL_ROUTE));
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return Payment|MockInterface
     */
    private function mockPayPalPayment()
    {
        if ($this->payPalPayment === null) {
            $this->payPalPayment = Mockery::mock(Payment::class);
            $this->payPalPayment->shouldIgnoreMissing()->asUndefined();

            app()->extend(
                Payment::class,
                function () {
                    return $this->payPalPayment;
                }
            );
        }

        return $this->payPalPayment;
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    private function mockPayPalPaymentId()
    {
        if (!$this->mockPayPalPayment()->id) {
            $this->mockPayPalPayment()->id = uniqid('paypal-payment', false);
        }

        return $this->mockPayPalPayment()->id;
    }
}
