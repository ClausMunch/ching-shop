<?php

namespace ChingShop\Modules\Sales\Http\Controllers\Customer;

use Analytics;
use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Sales\Domain\Clerk;
use ChingShop\Modules\Sales\Domain\Order\Order;
use ChingShop\Modules\Sales\Domain\Order\OrderItem;
use ChingShop\Modules\Sales\Domain\PayPal\PayPalRepository;
use ChingShop\Modules\Sales\Http\Requests\Customer\PayPalReturnRequest;
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

    /** @var PayPalRepository */
    private $payPalRepository;

    /**
     * @param Clerk            $clerk
     * @param WebUi            $webUi
     * @param PayPalRepository $payPalRepository
     */
    public function __construct(
        Clerk $clerk,
        WebUi $webUi,
        PayPalRepository $payPalRepository
    ) {
        $this->clerk = $clerk;
        $this->webUi = $webUi;
        $this->payPalRepository = $payPalRepository;
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return RedirectResponse
     */
    public function startAction()
    {
        $payPalCheckout = $this->payPalRepository->makeCheckout(
            $this->clerk->basket()
        );
        $this->payPalRepository->createInitiation($payPalCheckout);

        return $this->webUi->redirectAway($payPalCheckout->approvalUrl());
    }

    /**
     * @param PayPalReturnRequest $request
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     *
     * @return RedirectResponse
     */
    public function returnAction(PayPalReturnRequest $request)
    {
        if (!$request->isSuccessful()) {
            return $this->failure();
        }

        // check stock here

        $order = $this->payPalRepository->executePayment(
            $request->paymentId(),
            $request->payerId()
        );

        if ($order && $order->id) {
            $this->webUi->successMessage('Thank you; your order is confirmed.');

            $this->trackOrder($order);

            return $this->webUi->redirect(
                'sales.customer.order.view',
                [$order->publicId()]
            );
        }

        return $this->failure();
    }

    /**
     * @return RedirectResponse
     */
    public function cancelAction()
    {
        $this->webUi->warningMessage(
            'No worries, your PayPal payment was cancelled.
            Please choose a payment method'
        );

        return $this->webUi->redirect('sales.customer.checkout.choose-payment');
    }

    /**
     * @return RedirectResponse
     */
    private function failure()
    {
        $this->webUi->errorMessage(
            'Something went wrong whilst setting up the PayPal payment.
            You have not been charged. Please try again.'
        );

        return $this->webUi->redirect('sales.customer.checkout.choose-payment');
    }

    /**
     * @param Order $order
     */
    private function trackOrder(Order $order)
    {
        Analytics::enableEcommerceTracking();
        Analytics::ecommerceAddTransaction(
            $order->publicId(),
            config('app.name'),
            $order->totalPrice(),
            0.00,
            0.00
        );
        $order->orderItems->each(
            function (OrderItem $item) {
                Analytics::ecommerceAddItem(
                    $item->id,
                    $item->name(),
                    $item->sku(),
                    $item->category()->name,
                    $item->priceAsFloat(),
                    1
                );
            }
        );
    }
}
