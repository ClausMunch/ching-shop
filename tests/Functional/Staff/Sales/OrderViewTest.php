<?php

namespace Testing\Functional\Staff\Sales;

use Testing\Functional\FunctionalTest;
use Testing\Functional\Staff\StaffUser;
use Testing\Functional\Util\SalesInteractions;

/**
 * Test viewing orders in the staff area.
 *
 * Class OrderViewTest
 * @package Testing\Functional\Staff\Sales
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
            ->visit(route('shopping.staff.orders.index'))
            ->dontSee('orders')
            ->dontSee('Orders');
    }

    /**
     * Should be able to view the orders index.
     */
    public function testCanVisitOrdersIndex()
    {
        $this->actingAs($this->staffUser())
            ->visit(route('shopping.staff.orders.index'))
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
            ->visit(route('shopping.staff.orders.index'));

        // Then we should see the order there.
        $this->see($order->publicId());
    }
}
