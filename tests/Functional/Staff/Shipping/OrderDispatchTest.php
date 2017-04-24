<?php

namespace Testing\Functional\Staff\Shipping;

use ChingShop\Modules\Sales\Jobs\PrintOrderAddress;
use Queue;
use Testing\Functional\FunctionalTest;
use Testing\Functional\Staff\StaffUser;
use Testing\Functional\Util\SalesInteractions;

/**
 * Test order dispatch logging functionality.
 */
class OrderDispatchTest extends FunctionalTest
{
    use StaffUser, SalesInteractions;

    /**
     * A staff user should be able to mark an order as dispatched.
     */
    public function testCanMarkOrderAsDispatched()
    {
        // Given there is an order;
        $order = $this->completeOrder($this);

        // When a staff user views it;
        $this->actingAs($this->staffUser())
            ->visit(route('orders.index'))
            ->see($order->publicId());

        // And marks it as dispatched;
        $this->actingAs($this->staffUser())
            ->press("Mark #{$order->publicId()} as dispatched");

        // Then it should be shown as dispatched;
        $this->actingAs($this->staffUser())
            ->see('dispatched');
        $order = $order->fresh();
        $this->assertTrue(
            $order->hasBeenDispatched(),
            'Order should be logged as dispatched.'
        );
    }

    /**
     * Should be able to manually trigger the printing of an order address.
     */
    public function testCanPrintOrderAddress()
    {
        // Given there are no print jobs;
        $this->printQueueIsEmpty();

        // And there is an order;
        $order = $this->completeOrder($this);

        // When a staff user views it;
        $this->actingAs($this->staffUser())
            ->visit(route('orders.index'))
            ->see($order->publicId());

        // And clicks the print button;
        $this->actingAs($this->staffUser())
            ->press("Print #{$order->publicId()}")
            ->see("Sent print job for order #{$order->publicId()}.");

        // Then there should be a print job for it.
        $job = $this->printJobs()->pop();
        $content = json_decode($job->getRawBody());
        $this->assertEquals($order->publicId(), $content->order_id);
    }

    /**
     * Should be able to print a generic address.
     */
    public function testCanPrintGenericAddress()
    {
        // Given there are no print jobs;
        $this->printQueueIsEmpty();

        // And we are on the print address page;
        $this->actingAs($this->staffUser())->visit(route('print-address-form'));

        // And we fill in an address;
        $this->type(
            <<<ADR
Fooey McBar
23 Foo Street
FooBar District
Test Town
FOO BAR
GB
ADR
            ,
            'address'
        );

        // When we press the 'print' button;
        $this->press('Print');

        // Then a print job for that address should have been dispatched.
        $this->see('Sent print job for');
        $this->see('23 Foo Street');
        $job = $this->printJobs()->pop();
        $content = json_decode($job->getRawBody());
        $this->assertEquals('23 Foo Street', trim($content->address->line_one));
    }

    /**
     * @return \Illuminate\Contracts\Queue\Queue
     */
    private function printJobs(): \Illuminate\Contracts\Queue\Queue
    {
        return Queue::connection(PrintOrderAddress::QUEUE_CONNECTION);
    }

    /**
     * Clear all jobs from the print queue.
     */
    private function printQueueIsEmpty()
    {
        while ($this->printJobs()->pop()) {
            $this->printJobs()->pop();
        }
    }
}
