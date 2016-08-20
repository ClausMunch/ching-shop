<?php

namespace Testing\Functional\Sales;

use Testing\Functional\Customer\Sales\SalesInteractions;
use Testing\Functional\FunctionalTest;

/**
 * Class CheckoutAddressTest.
 *
 * @group sales
 * @group checkout
 */
class CheckoutAddressTest extends FunctionalTest
{
    use SalesInteractions;

    /**
     * Should be able to go to the checkout address section.
     */
    public function testCanGoToCheckoutAddress()
    {
        $this->actingAs($this->customerUser())
            ->visit(route('sales.customer.checkout.address'))
            ->see('Checkout')
            ->see('Address');
    }

    /**
     * Should be able to see a summary of the basket items in the address
     * section.
     */
    public function testCanSeeBasketSummary()
    {
        // Given we have an item in the basket;
        $product = $this->createProductAndAddToBasket($this);

        // When we got to the checkout address section;
        $this->actingAs($this->customerUser())
            ->visit(route('sales.customer.checkout.address'));

        // Then we should see the item in the summary
        $this->see($product->name);
    }

    /**
     * Should be able to save the address for the order.
     */
    public function testCanCompleteAddress()
    {
        // When we fill in the address during checkout;
        $address = $this->completeCheckoutAddress($this);

        // Then we should get a success message and see the address saved;
        $this->seePageIs(route('sales.customer.checkout.choose-payment'))
            ->see('saved')
            ->see($address->name)
            ->see($address->line_one)
            ->see($address->line_two)
            ->see($address->post_code)
            ->see($address->country);
    }

    /**
     * The address should be rejected if required fields are not given.
     */
    public function testRequiredAddressFields()
    {
        // Given we have an item in the basket;
        $this->createProductAndAddToBasket($this);

        // When we fill out the order address without the required fields;
        $this->actingAs($this->customerUser())
            ->visit(route('sales.customer.checkout.address'))
            ->type('only filling this line', 'line_two')
            ->press('Continue');

        // Then we should be redirected back to the address page;
        $this->seePageIs(route('sales.customer.checkout.address'));

        // And the problems should be explained.
        $this->see('name field is required')
            ->see('line one field is required')
            ->dontSee('line two field is required') // (because it's not)
            ->see('city field is required')
            ->see('post code field is required');
    }
}
