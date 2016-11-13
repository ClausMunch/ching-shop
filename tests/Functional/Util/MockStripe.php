<?php

namespace Testing\Functional\Util;

use Mockery;
use Mockery\MockInterface;
use Stripe\Charge;

/**
 * Mock out Stripe interactions in tests.
 */
trait MockStripe
{
    /** @var Charge|MockInterface */
    private $mockStripeCharge;

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
