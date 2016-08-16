<?php

namespace ChingShop\Modules\Sales\Http\Controllers\Customer;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Sales\Model\Clerk;
use ChingShop\Modules\Sales\Model\PayPal\PayPalCheckoutFactory;
use Illuminate\Http\RedirectResponse;

/**
 * Class PayPalController.
 */
class PayPalController extends Controller
{
    /** @var Clerk */
    private $clerk;

    /** @var WebUi */
    private $webUi;

    /** @var PayPalCheckoutFactory */
    private $checkoutFactory;

    /**
     * @param Clerk                 $clerk
     * @param WebUi                 $webUi
     * @param PayPalCheckoutFactory $checkoutFactory
     */
    public function __construct(
        Clerk $clerk,
        WebUi $webUi,
        PayPalCheckoutFactory $checkoutFactory
    ) {
        $this->clerk = $clerk;
        $this->webUi = $webUi;
        $this->checkoutFactory = $checkoutFactory;
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return RedirectResponse
     */
    public function startAction()
    {
        return $this->webUi->redirectAway(
            $this->checkoutFactory->makePayPalCheckout(
                $this->clerk->basket()
            )->approvalUrl()
        );
    }

    /**
     * @return RedirectResponse
     */
    public function returnAction()
    {
        return $this->webUi->redirectAway('TODO');
    }

    /**
     * @return RedirectResponse
     */
    public function cancelAction()
    {
        return $this->webUi->redirectAway('TODO');
    }
}
