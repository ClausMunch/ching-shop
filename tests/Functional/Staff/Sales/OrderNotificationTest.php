<?php

namespace Testing\Functional\Staff\Sales;

use ChingShop\Modules\Sales\Jobs\PrintOrderAddress;
use ChingShop\Modules\Sales\Notifications\StaffOrderNotification;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Support\Facades\Notification;
use Queue;
use Testing\Functional\FunctionalTest;
use Testing\Functional\Staff\StaffUser;
use Testing\Functional\Util\SalesInteractions;

/**
 * Test new order notifications are sent to staff.
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
            StaffOrderNotification::class,
            function (StaffOrderNotification $notification) {
                return $notification->order->id === $this->orders[0]->id;
            }
        );
    }

    /**
     * A print job should be dispatched for a new order's address.
     */
    public function testOrderAddressPrintJob()
    {
        // Given the print job queue is empty;
        $printJobConnection = Queue::connection(
            PrintOrderAddress::QUEUE_CONNECTION
        );
        do {
            $job = $printJobConnection->pop();
        } while ($job);

        // When a customer makes an order;
        $order = $this->completeOrder($this);

        // Then a print job for its shipping address should have been dispatched
        // on the print job queue.
        $job = $printJobConnection->pop();
        $this->assertInstanceOf(Job::class, $job);
        $content = json_decode($job->getRawBody());

        $this->assertEquals($order->publicId(), $content->order_id);
        $this->assertEquals($order->address->name, $content->address->name);
        $this->assertEquals(
            $order->address->line_one,
            $content->address->line_one
        );
        $this->assertEquals($order->address->city, $content->address->city);
        $this->assertEquals(
            $order->address->post_code,
            $content->address->post_code
        );
    }
}
