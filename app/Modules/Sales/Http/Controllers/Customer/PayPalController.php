<?php

namespace ChingShop\Modules\Sales\Http\Controllers\Customer;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Sales\Model\CheckoutAssistant;

/**
 * Class PayPalController.
 */
class PayPalController extends Controller
{
    /** @var CheckoutAssistant */
    private $checkoutAssistant;

    /** @var WebUi */
    private $webUi;

    /**
     * PayPalController constructor.
     *
     * @param CheckoutAssistant $checkoutAssistant
     * @param WebUi             $webUi
     */
    public function __construct(
        CheckoutAssistant $checkoutAssistant,
        WebUi $webUi
    ) {
        $this->checkoutAssistant = $checkoutAssistant;
        $this->webUi = $webUi;
    }

    public function startExpressCheckoutAction()
    {
    }
}
