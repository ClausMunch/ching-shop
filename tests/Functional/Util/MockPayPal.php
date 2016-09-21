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
        $this->mockPayPalPayment()->id = uniqid('paypal-payment', false);

        $this->mockPayPalPayment()
            ->shouldReceive('getApprovalLink')
            ->zeroOrMoreTimes()
            ->andReturn(
                route(
                    PayPalCheckout::RETURN_ROUTE,
                    [
                        'token'     => uniqid('paypal-token', false),
                        'paymentId' => $this->mockPayPalPayment()->id,
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
}
