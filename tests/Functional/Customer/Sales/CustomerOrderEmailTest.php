<?php

namespace Testing\Functional\Customer\Sales;

use ChingShop\Modules\Sales\Domain\Order\Order;
use ChingShop\Modules\Sales\Notifications\CustomerOrderNotification;
use ChingShop\Modules\Shipping\Notifications\CustomerDispatchNotification;
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

    /**
     * An email should be sent to a customer when their order is dispatched.
     *
     * @slowThreshold 1300
     */
    public function testCustomerOrderDispatchEmail()
    {
        // Given a customer has completed an order;
        $order = $this->completeOrder($this);

        // When a staff user marks the order as dispatched;
        Notification::fake();
        $this->actingAs($this->staffUser())
            ->visit(route('orders.index'))
            ->see($order->publicId())
            ->press("Mark #{$order->publicId()} as dispatched")
            ->see('dispatched');

        // Then the customer should have been sent an email about the dispatch.
        Notification::assertSentTo(
            $order,
            CustomerDispatchNotification::class,
            function (CustomerDispatchNotification $notification) use ($order) {
                return $notification->dispatch->order->id === $order->id;
            }
        );
    }
}
