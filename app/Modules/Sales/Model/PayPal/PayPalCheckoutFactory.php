<?php

namespace ChingShop\Modules\Sales\Model\PayPal;

use ChingShop\Modules\Sales\Model\Basket\Basket;
use PayPal\Rest\ApiContext;

/**
 * Class PayPalCheckoutFactory.
 */
class PayPalCheckoutFactory
{
    /** @var ApiContext */
    private $apiContext;

    /**
     * PayPalCheckoutFactory constructor.
     *
     * @param ApiContext $apiContext
     */
    public function __construct(ApiContext $apiContext)
    {
        $this->apiContext = $apiContext;
    }

    /**
     * @param Basket $basket
     *
     * @return PayPalCheckout
     */
    public function makePayPalCheckout(Basket $basket): PayPalCheckout
    {
        $basket->load(
            [
                'basketItems.productOption.product.prices',
                'address',
            ]
        );

        return new PayPalCheckout($basket, $this->apiContext);
    }
}
