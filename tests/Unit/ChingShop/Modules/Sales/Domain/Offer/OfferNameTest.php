<?php

namespace Testing\Unit\ChingShop\Modules\Sales\Domain\Offer;

use ChingShop\Modules\Sales\Domain\Offer\Offer;
use ChingShop\Modules\Sales\Domain\Offer\OfferName;
use Testing\TestCase;

/**
 * Test offer name presentation behaviour.
 */
class OfferNameTest extends TestCase
{
    /**
     * @param Offer  $offer
     * @param string $expectedName
     *
     * @dataProvider namesProvider()
     */
    public function testName(Offer $offer, string $expectedName)
    {
        $name = new OfferName($offer);
        $this->assertEquals($expectedName, (string) $name);
    }

    /**
     * @return array[]
     */
    public function namesProvider()
    {
        // effect, quantity, price, percentage, expected name
        return [
            // Price
            [
                new Offer(
                    [
                        'effect'   => 'absolute',
                        'quantity' => 1,
                        'price'    => 1000,
                    ]
                ),
                'Sale: £10',
            ],
            [
                new Offer(
                    [
                        'effect'   => 'absolute',
                        'quantity' => 2,
                        'price'    => 1000,
                    ]
                ),
                'Any 2 for £10',
            ],
            [
                new Offer(
                    [
                        'effect'   => 'absolute',
                        'quantity' => 3,
                        'price'    => 575,
                    ]
                ),
                'Any 3 for £5.75',
            ],
            [
                new Offer(
                    [
                        'effect'   => 'relative',
                        'quantity' => 1,
                        'price'    => 200,
                    ]
                ),
                '£2 off',
            ],
            [
                new Offer(
                    [
                        'effect'   => 'relative',
                        'quantity' => 2,
                        'price'    => 500,
                    ]
                ),
                '£5 off when you buy 2',
            ],
            // Percentage
            [
                new Offer(
                    [
                        'effect'     => 'absolute',
                        'quantity'   => 1,
                        'percentage' => 70,
                    ]
                ),
                '30% off',
            ],
            [
                new Offer(
                    [
                        'effect'     => 'absolute',
                        'quantity'   => 2,
                        'percentage' => 50,
                    ]
                ),
                '50% off when you buy 2',
            ],
            [
                new Offer(
                    [
                        'effect'     => 'relative',
                        'quantity'   => 1,
                        'percentage' => 80,
                    ]
                ),
                '80% off',
            ],
            [
                new Offer(
                    [
                        'effect'     => 'relative',
                        'quantity'   => 3,
                        'percentage' => 25,
                    ]
                ),
                '25% off when you buy 3',
            ],
        ];
    }
}
