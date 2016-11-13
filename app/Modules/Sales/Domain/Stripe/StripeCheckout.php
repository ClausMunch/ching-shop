<?php

namespace ChingShop\Modules\Sales\Domain\Stripe;

use ChingShop\Modules\Sales\Domain\Basket\Basket;
use ChingShop\Modules\Sales\Domain\Basket\BasketPresenter;
use ChingShop\Modules\Sales\Domain\Clerk;
use ChingShop\Modules\Sales\Domain\Order\Order;
use ChingShop\Modules\Sales\Domain\Payment\Cashier;
use Log;
use Stripe\Charge;

/**
 * Stripe checkout behaviour.
 */
class StripeCheckout
{
    /** @var Clerk */
    private $clerk;

    /** @var Cashier */
    private $cashier;

    /** @var Charge */
    private $charge;

    /**
     * @param Clerk   $clerk
     * @param Cashier $cashier
     * @param Charge  $charge
     */
    public function __construct(Clerk $clerk, Cashier $cashier, Charge $charge)
    {
        $this->clerk = $clerk;
        $this->cashier = $cashier;
        $this->charge = $charge;
    }

    /**
     * @param string $stripeToken
     *
     * @throws \Exception
     *
     * @return Order
     */
    public function pay(string $stripeToken): Order
    {
        $total = $this->basket()->subUnitPrice();
        Log::info(
            "Taking Stripe payment for `£{$total}` with token `{$stripeToken}`."
        );
        $settlement = StripeSettlement::create(['token' => $stripeToken]);
        $order = $this->cashier->settle($this->basket(), $settlement);

        Log::info(
            "Charging for order `{$order->publicId()}` with Stripe."
        );
        /** @var Charge $charge */
        $charge = $this->charge->create(
            [
                'amount'      => $total,
                'currency'    => 'gbp',
                'source'      => $stripeToken,
                'description' => "ching-shop.com order {$order->publicId()}",
            ]
        );

        Log::info(
            "Took payment for for order `{$order->publicId()}` with Stripe."
        );

        if ($charge instanceof Charge) {
            $settlement->fillFromCharge($charge)->save();
        }

        return $order;
    }

    /**
     * @return Basket
     */
    private function basket(): Basket
    {
        /** @var Basket $basket */
        $basket = $this->clerk->basket()->fresh();
        if ($basket instanceof BasketPresenter) {
            $basket = $basket->getWrappedObject();
        }

        return $basket;
    }
}
