<?php

namespace ChingShop\Modules\Shipping\Http\Controllers\Staff;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Sales\Domain\Order\Order;
use ChingShop\Modules\Shipping\Domain\Dispatch;
use Illuminate\Http\Request;

/**
 * Staff order dispatch interactions.
 */
class DispatchController extends Controller
{
    /** @var Dispatch */
    private $dispatchResource;

    /** @var Order */
    private $orderResource;

    /** @var WebUi */
    private $webUi;

    /**
     * DispatchController constructor.
     *
     * @param Dispatch $dispatchResource
     * @param Order    $orderResource
     * @param WebUi    $webUi
     */
    public function __construct(
        Dispatch $dispatchResource,
        Order $orderResource,
        WebUi $webUi
    ) {
        $this->dispatchResource = $dispatchResource;
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

        if ($order->dispatches->isEmpty()) {
            $order->dispatches()->save(new Dispatch());
            $this->webUi->successMessage(
                "Marked #{$order->publicId()} as dispatched."
            );
        }

        return $this->webUi->redirect('orders.index');
    }
}
