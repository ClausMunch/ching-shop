<?php

namespace ChingShop\Modules\Sales\Http\Controllers\Customer;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Sales\Domain\Clerk;
use ChingShop\Modules\Sales\Domain\Order\Order;
use ChingShop\Modules\Sales\Domain\Order\TracksOrders;
use ChingShop\Modules\Sales\Domain\Payment\StockAllocationException;
use ChingShop\Modules\Sales\Domain\PayPal\PayPalRepository;
use ChingShop\Modules\Sales\Http\Requests\Customer\PayPalReturnRequest;
use Illuminate\Http\RedirectResponse;
use Log;
use Throwable;

/**
 * Class PayPalController.
 */
class PayPalController extends Controller
{
    use TracksOrders;

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

        try {
            /** @var Order $order */
            $order = $this->payPalRepository->executePayment(
                $request->paymentId(),
                $request->payerId()
            );
        } catch (StockAllocationException $e) {
            Log::error(
                "Error executing PayPal payment: {$e->getMessage()}."
            );

            $this->webUi->warningMessage(
                'Sorry, we were not able to allocate stock for your order.'
            );

            if (isset($order)) {
                $order->deAllocate();
            }

            return $this->webUi->redirect(
                'sales.customer.checkout.choose-payment'
            );
        } catch (Throwable $e) {
            Log::error(
                "Error executing PayPal payment: {$e->getMessage()}."
            );

            return $this->failure();
        }

        $this->webUi->successMessage('Thank you; your order is confirmed.');
        $this->trackOrder($order);

        return $this->webUi->redirect(
            'sales.customer.order.view',
            [$order->publicId()]
        );
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
}
