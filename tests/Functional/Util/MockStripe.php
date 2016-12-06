<?php

namespace Testing\Functional\Util;

use ChingShop\Modules\Sales\Http\Requests\Customer\StripePaymentRequest;
use ChingShop\Modules\User\Model\User;
use Mockery;
use Mockery\MockInterface;
use Stripe\Charge;
use Testing\Functional\FunctionalTest;

/**
 * Mock out Stripe interactions in tests.
 */
trait MockStripe
{
    /** @var Charge|MockInterface */
    private $mockStripeCharge;

    /**
     * @param FunctionalTest $test
     * @param User           $user
     *
     * @return FunctionalTest
     * @throws \InvalidArgumentException
     */
    private function payWithStripe(FunctionalTest $test, User $user)
    {
        $this->customerWillPayWithStripe();

        return $test->actingAs($user)
            ->visit(route('sales.customer.checkout.choose-payment'))
            ->post(
                route('sales.customer.stripe.pay'),
                [
                    StripePaymentRequest::TOKEN => 'mock-token',
                    'csrf_token'                => csrf_token(),
                ]
            )
            ->followRedirects();
    }

    /**
     * @param string $status
     *
     * @throws \InvalidArgumentException
     */
    private function customerWillPayWithStripe(string $status = 'succeeded')
    {
        $this->mockStripeCharge()
            ->shouldReceive('create')
            ->zeroOrMoreTimes()
            ->andReturn($status);
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return MockInterface
     */
    private function mockStripeCharge(): MockInterface
    {
        if ($this->mockStripeCharge === null) {
            $this->mockStripeCharge = Mockery::mock(Charge::class);
            $this->mockStripeCharge->shouldIgnoreMissing()->asUndefined();

            app()->extend(
                Charge::class,
                function () {
                    return $this->mockStripeCharge;
                }
            );
        }

        return $this->mockStripeCharge;
    }
}
