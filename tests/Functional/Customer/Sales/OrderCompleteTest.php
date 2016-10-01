<?php

namespace Testing\Functional\Customer\Sales;

use Testing\Functional\FunctionalTest;
use Testing\Functional\Util\SalesInteractions;

/**
 * Class OrderCompleteTest.
 *
 * @group sales
 * @group checkout
 */
class OrderCompleteTest extends FunctionalTest
{
    use SalesInteractions;

    /**
     * Should be able to see the contents of the order on the order completion
     * page.
     *
     * @slowThreshold 2000
     */
    public function testCanSeeOrderItems()
    {
        // When I complete an order;
        $this->completeOrder($this);

        // Then I should be able to see the items I have ordered on the order
        // completion page.
        foreach ($this->productsInBasket as $product) {
            $this->see($product->name);
            $this->see($product->options->first()->label);
        }
    }

    /**
     * Should be able to see the delivery address on the order completion page.
     *
     * @slowThreshold 2000
     */
    public function testCanSeeOrderAddress()
    {
        // When I complete an order;
        $this->completeOrder($this);

        // Then I should be able to see my delivery address on the order
        // completion page.
        $this->seePageIs(
            route('sales.customer.order.view', [$this->orders[0] ?? ''])
        );
        $this->see($this->address->name);
        $this->see($this->address->line_one);
        $this->see($this->address->city);
        $this->see($this->address->post_code);
        $this->see($this->address->country_code);
    }
}
