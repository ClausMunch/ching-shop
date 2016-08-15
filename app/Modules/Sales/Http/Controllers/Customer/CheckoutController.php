<?php

namespace ChingShop\Modules\Sales\Http\Controllers\Customer;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Data\Model\Country;
use ChingShop\Modules\Sales\Http\Requests\Customer\SaveAddressRequest;
use ChingShop\Modules\Sales\Model\CheckoutAssistant;
use ChingShop\Modules\Sales\Model\Clerk;
use PayPal\Api\CountryCode;

class CheckoutController extends Controller
{
    /** @var Clerk */
    private $clerk;

    /** @var CheckoutAssistant */
    private $checkoutAssistant;

    /** @var WebUi */
    private $webUi;

    /**
     * @param Clerk             $clerk
     * @param CheckoutAssistant $checkoutAssistant
     * @param WebUi             $webUi
     */
    public function __construct(
        Clerk $clerk,
        CheckoutAssistant $checkoutAssistant,
        WebUi $webUi
    ) {
        $this->clerk = $clerk;
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

        return $this->webUi->redirect('sales.customer.checkout.choose-payment');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function choosePaymentAction()
    {
        return $this->webUi->view(
            'customer.checkout.choose-payment',
            ['progress' => 50]
        );
    }
}
