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
    private static $attributes = [
        'id',
        'amount',
        'balance_transaction',
        'captured',
        'created',
        'currency',
        'description',
        'failure_code',
        'failure_message',
        'paid',
        'status',
    ];

    /** @var Charge|MockInterface */
    private $mockStripeCharge;

    /**
     * @param FunctionalTest $test
     * @param User           $user
     *
     * @throws \InvalidArgumentException
     *
     * @return FunctionalTest
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
     * @throws \InvalidArgumentException
     */
    private function customerWillPayWithStripe()
    {
        $this->mockStripeCharge()
            ->shouldReceive('create')
            ->zeroOrMoreTimes()
            ->andReturnSelf();
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
            $this->mockStripeCharge->shouldReceive('__construct')->passthru();
            $this->mockStripeCharge->shouldIgnoreMissing()->asUndefined();
            /** @noinspection ImplicitMagicMethodCallInspection */
            $this->mockStripeCharge->__construct(); // Work in constructor :(.

            foreach (self::$attributes as $attribute) {
                $this->mockStripeCharge->{$attribute} = 'foobar';
            }

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
