<?php

namespace ChingShop\Modules\Sales\Http\Controllers\Customer;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Sales\Domain\Order\Order;
use ChingShop\Modules\Sales\Domain\Order\TracksOrders;
use ChingShop\Modules\Sales\Domain\Stripe\StripeCheckout;
use ChingShop\Modules\Sales\Http\Requests\Customer\StripePaymentRequest;
use Exception;
use Log;
use Stripe\Error\Card;

/**
 * Stripe card payment actions
 */
class StripeController extends Controller
{
    use TracksOrders;

    /** @var StripeCheckout */
    private $checkout;

    /** @var WebUi */
    private $webUi;

    /**
     * StripeController constructor.
     *
     * @param StripeCheckout $checkout
     * @param WebUi          $webUi
     */
    public function __construct(StripeCheckout $checkout, WebUi $webUi)
    {
        $this->checkout = $checkout;
        $this->webUi = $webUi;
    }

    /**
     * @param StripePaymentRequest $request
     *
     * @return string
     * @throws \Exception
     */
    public function payAction(StripePaymentRequest $request)
    {
        try {
            /** @var Order $order */
            $order = $this->checkout->pay($request->stripeToken());
        } catch (Card $e) {
            Log::warning(
                sprintf(
                    'Stripe | mess: %s | code: %s | stripe: %s | param: %s',
                    $e->getMessage(),
                    $e->getDeclineCode(),
                    $e->getStripeCode(),
                    $e->getStripeParam()
                )
            );
            $this->webUi->errorMessage(
                "Sorry, the card payment was not successful: {$e->getMessage()}"
            );

            if (isset($order)) {
                $order->deAllocate();
            }

            return $this->webUi->redirect(
                'sales.customer.checkout.choose-payment'
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());

            $this->webUi->errorMessage(
                'Sorry, something went wrong during payment. Please contact us.'
            );

            return $this->webUi->redirect(
                'sales.customer.checkout.choose-payment'
            );
        }

        $this->trackOrder($order);

        $this->webUi->successMessage('Thank you; your order is confirmed.');

        return $this->webUi->redirect(
            'sales.customer.order.view',
            [$order->publicId()]
        );
    }
}
