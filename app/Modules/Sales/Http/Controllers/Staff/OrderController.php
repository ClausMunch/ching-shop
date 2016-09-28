<?php

namespace ChingShop\Modules\Sales\Http\Controllers\Staff;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Sales\Domain\Order\OrderRepository;
use Illuminate\Contracts\View\View;

/**
 * Staff order management actions.
 *
 * Class OrderController
 * @package ChingShop\Modules\Sales\Http\Controllers\Staff
 */
class OrderController extends Controller
{

    /** @var OrderRepository */
    private $orderRepository;

    /** @var WebUi */
    private $webUi;

    /**
     * @param OrderRepository $orderRepository
     * @param WebUi           $webUi
     */
    public function __construct(
        OrderRepository $orderRepository,
        WebUi $webUi
    ) {
        $this->orderRepository = $orderRepository;
        $this->webUi = $webUi;
    }

    /**
     * @return View
     */
    public function index()
    {
        $orders = $this->orderRepository->all();

        return $this->buildView('index', compact('orders'));
    }

    /**
     * @param $name
     * @param array $bindData
     *
     * @return View
     */
    private function buildView($name, array $bindData = []): View
    {
        return $this->webUi->view(
            "sales::staff.orders.{$name}",
            $bindData
        );
    }
}
