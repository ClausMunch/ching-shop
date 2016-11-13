<?php

namespace ChingShop\Modules\Sales\Http\Controllers\Customer;

use Analytics;
use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Data\Model\Country;
use ChingShop\Modules\Sales\Domain\CheckoutAssistant;
use ChingShop\Modules\Sales\Http\Requests\Customer\SaveAddressRequest;

/**
 * Class CheckoutController.
 */
class CheckoutController extends Controller
{
    /** @var CheckoutAssistant */
    private $checkoutAssistant;

    /** @var WebUi */
    private $webUi;

    /**
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

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function addressAction()
    {
        return $this->webUi->view(
            'customer.checkout.address',
            [
                'progress'  => 25,
                'countries' => Country::CODES,
            ]
        );
    }

    /**
     * @param SaveAddressRequest $request
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     *
     * @return string
     */
    public function saveAddressAction(SaveAddressRequest $request)
    {
        $this->checkoutAssistant->saveAddress($request->getAddressFields());

        $this->webUi->successMessage(
            '&#10004; The delivery address for your order has been saved.'
        );

        Analytics::trackEvent('address', 'add');

        return $this->webUi->redirect('sales.customer.checkout.choose-payment');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function choosePaymentAction()
    {
        return $this->webUi->view(
            'customer.checkout.choose-payment',
            [
                'basket'      => $this->checkoutAssistant->basket(),
                'progress'    => 50,
                'stripeNonce' => random_int(0, PHP_INT_MAX),
            ]
        );
    }
}
