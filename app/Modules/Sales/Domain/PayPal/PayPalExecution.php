<?php

namespace ChingShop\Modules\Sales\Domain\PayPal;

use ChingShop\Modules\Sales\Domain\Basket\Basket;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;

/**
 * Wrapper around work to execute a PayPal payment after return from PayPal
 * checkout.
 *
 * Class PayPalExecution
 * @package ChingShop\Modules\Sales\Domain\PayPal
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
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isApproved(): bool
    {
        return strtolower($this->payment()->getState()) === 'approved';
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
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
     * @return Payment
     * @throws \InvalidArgumentException
     */
    private function payment(): Payment
    {
        if ($this->payment === null) {
            $this->payment = $this->return
                ->payment()
                ->execute($this->execution(), $this->context);
        }

        return $this->payment;
    }

    /**
     * @return Amount
     * @throws \InvalidArgumentException
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
     * @return Transaction
     * @throws \InvalidArgumentException
     */
    private function transaction(): Transaction
    {
        return (new Transaction())->setAmount($this->amount());
    }

    /**
     * @return PaymentExecution
     * @throws \InvalidArgumentException
     */
    private function execution(): PaymentExecution
    {
        return (new PaymentExecution())
            ->setPayerId($this->return->payerId())
            ->addTransaction($this->transaction());
    }
}
