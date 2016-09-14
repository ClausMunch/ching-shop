<?php

namespace ChingShop\Modules\Sales\Domain\PayPal;

use ChingShop\Modules\Sales\Domain\Basket\Basket;
use ChingShop\Modules\Sales\Domain\Order;
use ChingShop\Modules\Sales\Domain\Payment\Cashier;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Rest\ApiContext;
use Psr\Log\LoggerInterface;

/**
 * Manages persistence of PayPal-related models.
 *
 * Class PayPalCheckoutFactory.
 */
class PayPalRepository
{
    /** @var ApiContext */
    private $apiContext;

    /** @var Cashier */
    private $cashier;

    /** @var LoggerInterface */
    private $log;

    /**
     * PayPalCheckoutFactory constructor.
     *
     * @param ApiContext      $apiContext
     * @param Cashier         $cashier
     * @param LoggerInterface $logger
     */
    public function __construct(
        ApiContext $apiContext,
        Cashier $cashier,
        LoggerInterface $logger
    ) {
        $this->apiContext = $apiContext;
        $this->cashier = $cashier;
        $this->log = $logger;
    }

    /**
     * @param Basket $basket
     *
     * @return PayPalCheckout
     */
    public function makeCheckout(Basket $basket): PayPalCheckout
    {
        $this->log->info("Starting PayPal checkout for basket {$basket->id}");
        $basket->load(
            [
                'basketItems.productOption.product.prices',
                'address',
            ]
        );

        return new PayPalCheckout($basket, $this->apiContext);
    }

    /**
     * @param PayPalCheckout $payPalCheckout
     *
     * @throws \InvalidArgumentException
     *
     * @return PayPalInitiation
     */
    public function createInitiation(
        PayPalCheckout $payPalCheckout
    ): PayPalInitiation {
        /** @var PayPalInitiation $payPalInitiation */
        $payPalInitiation = PayPalInitiation::firstOrNew(
            [
                'payment_id' => $payPalCheckout->paymentId(),
                'amount'     => $payPalCheckout->amountTotal(),
            ]
        );

        $payPalInitiation->basket()->associate($payPalCheckout->basketId());
        $payPalInitiation->save();

        return $payPalInitiation;
    }

    /**
     * @param string $paymentId
     *
     * @return PayPalInitiation
     */
    public function loadInitiation(string $paymentId): PayPalInitiation
    {
        return PayPalInitiation::where(
            'payment_id',
            '=',
            $paymentId
        )->first() ?? new PayPalInitiation();
    }

    /**
     * @param string $paymentId
     * @param string $payerId
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     *
     * @return Order
     */
    public function executePayment(string $paymentId, string $payerId)
    {
        $this->log->info("Executing PayPal payment {$paymentId} / {$payerId}");
        $execution = $this->createExecution($paymentId, $payerId);

        try {
            if ($execution->isApproved()) {
                $settlement = PayPalSettlement::create(
                    [
                        'payment_id' => $paymentId,
                        'payer_id'   => $payerId,
                    ]
                );

                return $this->cashier->settle(
                    $execution->basket(),
                    $settlement
                );
            }
        } catch (\Throwable $e) {
            $this->log->error(
                sprintf(
                    'Error executing PayPal payment %s / %s: %s',
                    $paymentId,
                    $payerId,
                    $e->getMessage()
                )
            );
        }

        return new Order();
    }

    /**
     * @param string $paymentId
     * @param string $payerId
     *
     * @return PayPalExecution
     */
    private function createExecution(string $paymentId, string $payerId)
    {
        return new PayPalExecution(
            $this->loadInitiation($paymentId),
            new PayPalReturn(
                Payment::get($paymentId, $this->apiContext),
                $payerId
            ),
            $this->apiContext
        );
    }
}
