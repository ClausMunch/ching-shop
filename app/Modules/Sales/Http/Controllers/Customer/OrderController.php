<?php

namespace ChingShop\Modules\Sales\Http\Controllers\Customer;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Sales\Domain\Order;

/**
 * Controller for viewing and managing orders.
 *
 * Class OrderController
 */
class OrderController extends Controller
{
    /** @var WebUi */
    private $webUi;

    /**
     * OrderController constructor.
     *
     * @param WebUi $webUi
     */
    public function __construct(WebUi $webUi)
    {
        $this->webUi = $webUi;
    }

    /**
     * @param Order $order
     *
     * @return string
     */
    public function viewAction(Order $order)
    {
        return $this->webUi->view(
            'customer.orders.view',
            [
                'order' => $order,
            ]
        );
    }
}
