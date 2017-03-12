<?php

namespace Testing\Functional\Customer\Sales;

use ChingShop\Modules\Sales\Domain\Order\Order;
use ChingShop\Modules\Sales\Notifications\CustomerOrderNotification;
use Illuminate\Support\Facades\Notification;
use Testing\Functional\FunctionalTest;
use Testing\Functional\Staff\StaffUser;
use Testing\Functional\Util\SalesInteractions;

/**
 * Test new order notifications are sent to staff.
 */
class CustomerOrderEmailTest extends FunctionalTest
{
    use StaffUser, SalesInteractions;

    /**
     * An email should be sent to a customer when they make an order.
     *
     * @slowThreshold 1300
     */
    public function testNewOrderCustomerEmail()
    {
        // When a customer makes an order;
        Notification::fake();

        $this->completeOrder($this);

        $order = Order::wherePublicId(
            $this->getElementText('#order-id')
        )->firstOrFail();

        // Then they should have been sent an email about it.
        Notification::assertSentTo(
            $order,
            CustomerOrderNotification::class,
            function (CustomerOrderNotification $notification) use ($order) {
                return $notification->order->id === $order->id;
            }
        );
    }
}
