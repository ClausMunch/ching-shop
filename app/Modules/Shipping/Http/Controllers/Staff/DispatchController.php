<?php

namespace ChingShop\Modules\Shipping\Http\Controllers\Staff;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Sales\Domain\Address;
use ChingShop\Modules\Sales\Domain\Order\Order;
use ChingShop\Modules\Sales\Jobs\PrintOrderAddress;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Staff order dispatch interactions.
 */
class DispatchController extends Controller
{
    /** @var Order */
    private $orderResource;

    /** @var WebUi */
    private $webUi;

    /**
     * @param Order $orderResource
     * @param WebUi $webUi
     */
    public function __construct(Order $orderResource, WebUi $webUi)
    {
        $this->orderResource = $orderResource;
        $this->webUi = $webUi;
    }

    /**
     * @param Request $request
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $order = $this->findOrder($request);

        if ($order->markAsDispatched()) {
            $this->webUi->successMessage(
                "Marked #{$order->publicId()} as dispatched."
            );
        }

        return $this->webUi->redirect('orders.index');
    }

    /**
     * @param Request $request
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return RedirectResponse
     */
    public function printAddress(Request $request): RedirectResponse
    {
        $order = $this->findOrder($request);

        PrintOrderAddress::dispatch($order->address);

        $this->webUi->successMessage(
            "Sent print job for order #{$order->publicId()}."
        );

        return $this->webUi->redirect('orders.index');
    }

    /**
     * @return View
     */
    public function printAddressForm(): View
    {
        return $this->webUi->view('sales::staff.address.print');
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function printGenericAddress(Request $request): RedirectResponse
    {
        $address = Address::fromString($request->get('address'));
        PrintOrderAddress::dispatch(
            $address
        );

        $this->webUi->successMessage(
            "Sent print job for:\n{$address->__toString()}."
        );

        return $this->webUi->redirect('print-address-form');
    }

    /**
     * @param Request $request
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return Order
     */
    private function findOrder(Request $request): Order
    {
        $order = $this->orderResource->where(
            'id',
            '=',
            $request->get('order-id')
        )->firstOrFail();

        return $order;
    }
}
