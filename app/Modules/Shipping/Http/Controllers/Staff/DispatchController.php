<?php

namespace ChingShop\Modules\Shipping\Http\Controllers\Staff;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Sales\Domain\Order\Order;
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
        /** @var Order $order */
        $order = $this->orderResource->where(
            'id',
            '=',
            $request->get('order-id')
        )->firstOrFail();

        if ($order->markAsDispatched()) {
            $this->webUi->successMessage(
                "Marked #{$order->publicId()} as dispatched."
            );
        }

        return $this->webUi->redirect('orders.index');
    }
}
