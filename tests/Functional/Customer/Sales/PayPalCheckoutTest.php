<?php

namespace Testing\Functional\Customer\Sales;

use Symfony\Component\HttpFoundation\Response;
use Testing\Functional\Browser;
use Testing\Functional\FunctionalTest;

class PayPalCheckoutTest extends FunctionalTest
{
    use PayPalTestRequirements, SalesInteractions, Browser;

    /** @var string */
    private $orderNumber;

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

        // When we select PayPal as the payment method;
        $this->pressPayByPayPal();

        // And complete the PayPal checkout process;
        $this->completePayPalCheckout($this->response->getTargetUrl());
        $this->seePageIs(
            route('sales.customer.order.view', [$this->orderNumber])
        );

        // Then our order should be completed.
        $this->see('your order is confirmed');
    }

    /**
     * Press the 'Pay by PayPal' button.
     */
    private function pressPayByPayPal()
    {
        $form = $this->getForm('Pay with PayPal');
        $this->call($form->getMethod(), $form->getUri());

        // Then we should be re-directed to PayPal.
        $this->assertResponseStatus(Response::HTTP_FOUND);
        $this->assertStringStartsWith(
            config('payment.paypal.base-url'),
            $this->response->getTargetUrl()
        );
    }

    /**
     * @param string $startUrl
     *
     * @return void
     */
    private function completePayPalCheckout(string $startUrl)
    {
        $this->browser()->start($startUrl);
        $this->browser()->wait(5000);
        $this->browser()->waitForSelector('iframe[title*="Log In"]');
        $this->browser()->wait(5000);
        $this->browser()->switchToChildFrame(0);
        $this->browser()->wait(5000);
        $this->browserScreenShot(__LINE__);
        $this->browser()->wait(5000);
        $this->browser()->waitForSelector('form');
        $this->browser()->waitForSelector('input#email');
        $this->browser()->waitForSelector('input#password');
        $this->browser()->wait(5000);
        $this->browser()->fillFormSelectors(
            'form',
            [
                'input#email'    => config(
                    'payment.paypal.test-buyer.email'
                ),
                'input#password' => config(
                    'payment.paypal.test-buyer.password'
                ),
            ],
            $submit = true
        );
        $this->browser()->switchToParentFrame();
        $this->browser()->waitForSelector('#confirmButtonTop');
        $this->browser()->click('#confirmButtonTop');
        $this->browser()->waitForText('your order is confirmed');

        $this->browser()->run();

        $this->markTestIncomplete(
            'PayPal checkout integration test incomplete.'
        );

        $this->assertContains(
            'your order is confirmed',
            $this->browser()->getCurrentPageContent(),
            implode("\n", $this->browser()->getOutput())
        );

        // Move internal test crawler to resulting page.
        $this->actingAs($this->customerUser())
            ->visit($this->browser()->getCurrentUrl());
        $this->see('#order-id');
        $this->orderNumber = $this->getElementText('#order-id');
    }
}
