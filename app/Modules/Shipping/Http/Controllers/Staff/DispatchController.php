<?php

namespace ChingShop\Modules\Shipping\Http\Controllers\Staff;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Sales\Domain\Order\Order;
use ChingShop\Modules\Sales\Jobs\PrintOrderAddress;
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
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
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function printAddress(Request $request)
    {
        $order = $this->findOrder($request);

        PrintOrderAddress::dispatch($order->address);

        $this->webUi->successMessage(
            "Sent print job for order #{$order->publicId()}."
        );

        return $this->webUi->redirect('orders.index');
    }

    /**
     * @param Request $request
     *
     * @return Order
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
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
