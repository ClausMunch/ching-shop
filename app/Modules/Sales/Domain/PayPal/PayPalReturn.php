<?php

namespace ChingShop\Modules\Sales\Domain\PayPal;

use PayPal\Api\Payment;

/**
 * Container for data on return from PayPal checkout.
 *
 * Class PayPalReturn
 */
class PayPalReturn
{
    /** @var Payment */
    private $payment;

    /** @var string */
    private $payerId;

    /**
     * @param Payment $payment
     * @param string  $payerId
     */
    public function __construct(Payment $payment, string $payerId)
    {
        $this->payment = $payment;
        $this->payerId = $payerId;
    }

    /**
     * @return Payment
     */
    public function payment(): Payment
    {
        return $this->payment;
    }

    /**
     * @return string
     */
    public function payerId(): string
    {
        return $this->payerId;
    }
}
