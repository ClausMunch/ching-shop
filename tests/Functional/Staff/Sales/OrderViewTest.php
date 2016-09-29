<?php

namespace Testing\Functional\Staff\Sales;

use Testing\Functional\FunctionalTest;
use Testing\Functional\Staff\StaffUser;
use Testing\Functional\Util\SalesInteractions;

/**
 * Test viewing orders in the staff area.
 *
 * Class OrderViewTest
 */
class OrderViewTest extends FunctionalTest
{
    use StaffUser, SalesInteractions;

    /**
     * Should not be able to access staff orders page without being an
     * authenticated member of staff.
     *
     * @slowThreshold 800
     */
    public function testAuthRequired()
    {
        $this->actingAs($this->customerUser())
            ->visit(route('orders.index'))
            ->dontSee('orders')
            ->dontSee('Orders');
    }

    /**
     * Should be able to view the orders index.
     */
    public function testCanVisitOrdersIndex()
    {
        $this->actingAs($this->staffUser())
            ->visit(route('orders.index'))
            ->see('Orders');
    }

    /**
     * Should be able to see a customer order in the orders index.
     */
    public function testCanSeeCustomerOrderInIndex()
    {
        // Given a customer makes an order;
        $order = $this->completeOrder($this);

        // When we visit the staff orders index;
        $this->actingAs($this->staffUser())
            ->visit(route('orders.index'));

        // Then we should see the order there.
        $this->see($order->publicId());
    }

    /**
     * Should be able to view an individual customer order.
     */
    public function testCanViewIndividualCustomerOrder()
    {
        // Given a customer makes an order;
        $order = $this->completeOrder($this);

        // When we go to that order in the staff area;
        $this->actingAs($this->staffUser())
            ->visit(route('orders.index'))
            ->click($order->publicId());

        // Then we should see the order view.
        $this->see($order->publicId());
        $this->see($order->address->line_one);
        $this->see($order->payment->settlement->type());
        $this->see($order->totalPrice());
    }
}
