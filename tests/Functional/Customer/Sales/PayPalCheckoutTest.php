<?php

namespace Testing\Functional\Customer\Sales;

use Testing\Functional\Browser;
use Testing\Functional\FunctionalTest;

class PayPalCheckoutTest extends FunctionalTest
{
    use PayPalTestRequirements, SalesInteractions, Browser;

    /**
     * Skip tests if PayPal config is missing.
     */
    public function setUp()
    {
        parent::setUp();

        $this->checkPayPalTestRequirements($this);
    }

    /**
     * Should be able to get a PayPal checkout redirect.
     *
     * @slowThreshold 20000
     */
    public function testPayPalCheckout()
    {
        // Given we've got to the choose payment page;
        $this->completeCheckoutAddress($this);

        // And we choose to pay with PayPal;
        $form = $this->getForm('Pay with PayPal');
        $this->call($form->getMethod(), $form->getUri());

        $this->markTestIncomplete(
            'PayPal checkout integration test incomplete.'
        );
    }
}
