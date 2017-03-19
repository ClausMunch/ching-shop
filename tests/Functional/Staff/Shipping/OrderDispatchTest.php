<?php

namespace Testing\Functional\Staff\Shipping;

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
}
