<?php

namespace ChingShop\Modules\Sales\Listeners;

use ChingShop\Modules\Sales\Events\NewPayPalSettlementEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;
use PayPal\Api\Payment;
use PayPal\Rest\ApiContext;
use Throwable;

/**
 * Fetch and store the transaction id for a PayPal settlement.
 */
class SetPayPalTransactionId implements ShouldQueue
{
    /** @var Payment */
    private $paymentResource;

    /** @var ApiContext */
    private $apiContext;

    /**
     * @param Payment    $paymentResource
     * @param ApiContext $apiContext
     */
    public function __construct(
        Payment $paymentResource,
        ApiContext $apiContext
    ) {
        $this->paymentResource = $paymentResource;
        $this->apiContext = $apiContext;
    }

    /**
     * @param NewPayPalSettlementEvent $event
     */
    public function handle(NewPayPalSettlementEvent $event)
    {
        try {
            $event->settlement->transaction_id = $this->getTransactionId(
                $event->settlement->payment_id
            );
            $event->settlement->save();
        } catch (Throwable $e) {
            Log::warning(
                sprintf(
                    'Error whilst fetching transaction id for %s: %s',
                    $event->settlement->payment_id,
                    $e->getMessage()
                )
            );
        }
    }

    /**
     * @param string $paymentId
     *
     * @return string
     */
    private function getTransactionId(string $paymentId): string
    {
        $payment = $this->paymentResource->get($paymentId, $this->apiContext);
        $transactions = $payment->getTransactions();
        $relatedResources = $transactions[0]->getRelatedResources();
        $sale = $relatedResources[0]->getSale();

        return (string) $sale->getId();
    }
}
