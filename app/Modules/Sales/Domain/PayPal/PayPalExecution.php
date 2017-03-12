<?php

namespace ChingShop\Modules\Sales\Domain\PayPal;

use ChingShop\Modules\Sales\Domain\Basket\Basket;
use Log;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use Throwable;

/**
 * Wrapper around work to execute a PayPal payment after return from PayPal
 * checkout.
 *
 * Class PayPalExecution
 */
class PayPalExecution
{
    /** @var PayPalInitiation */
    private $initiation;

    /** @var PayPalReturn */
    private $return;

    /** @var ApiContext */
    private $context;

    /** @var Payment */
    private $payment;

    /**
     * PayPalExecution constructor.
     *
     * @param PayPalInitiation $initiation
     * @param PayPalReturn     $return
     * @param ApiContext       $context
     */
    public function __construct(
        PayPalInitiation $initiation,
        PayPalReturn $return,
        ApiContext $context
    ) {
        $this->initiation = $initiation;
        $this->return = $return;
        $this->context = $context;
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    public function approve(): bool
    {
        return strtolower($this->payment()->getState()) === 'approved';
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function message(): string
    {
        return (string) $this->payment()->getFailureReason();
    }

    /**
     * @return Basket
     */
    public function basket(): Basket
    {
        return $this->initiation->basket;
    }

    /**
     * @return string
     */
    public function payerEmail(): string
    {
        try {
            return (string) $this->payment()
                ->getPayer()
                ->getPayerInfo()
                ->getEmail();
        } catch (Throwable $e) {
            Log::error("Error getting PayPal payer email: {$e->getMessage()}");
        }

        return '';
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return Payment
     */
    private function payment(): Payment
    {
        if ($this->payment === null) {
            $this->payment = $this->return->payment();
            $this->payment->execute($this->execution(), $this->context);
        }

        return $this->payment;
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return Amount
     */
    private function amount(): Amount
    {
        return (new Amount())
            ->setCurrency('GBP')
            ->setTotal($this->initiation->amount)
            ->setDetails(
                (new Details())
                    ->setShipping(0)
                    ->setTax(0)
                    ->setSubtotal($this->initiation->amount)
            );
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return Transaction
     */
    private function transaction(): Transaction
    {
        return (new Transaction())->setAmount($this->amount());
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return PaymentExecution
     */
    private function execution(): PaymentExecution
    {
        return (new PaymentExecution())
            ->setPayerId($this->return->payerId())
            ->addTransaction($this->transaction());
    }
}
