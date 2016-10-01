<?php

namespace Testing\Functional\Staff\Sales;

use ChingShop\Modules\Sales\Notifications\NewOrderNotification;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Testing\Functional\FunctionalTest;
use Testing\Functional\Staff\StaffUser;
use Testing\Functional\Util\SalesInteractions;

/**
 * Test new order notifications are sent to staff.
 *
 * @package Testing\Functional\Staff\Sales
 */
class OrderNotificationTest extends FunctionalTest
{
    use StaffUser, SalesInteractions;

    /**
     * Notifications should be dispatched about a new order.
     *
     * @slowThreshold 1300
     */
    public function testNewOrderStaffNotification()
    {
        // Given a staff user exists;
        $this->staffUser();

        // When a customer makes an order;
        Notification::fake();
        $this->completeOrder($this);

        // Then the staff user should have been notified about it.
        Notification::assertSentTo(
            $this->staffUser(),
            NewOrderNotification::class,
            function (NewOrderNotification $notification) {
                return $notification->order->id === $this->orders[0]->id;
            }
        );
    }
}
