<?php

namespace Testing\Unit\ChingShop\Modules\Sales\Domain;

use ChingShop\Modules\Sales\Domain\Address;
use Testing\BrowserKitTestCase;

/**
 * Test shipping address unit behaviour.
 */
class AddressTest extends BrowserKitTestCase
{
    /**
     * Should be able to convert a line-broken string into an address.
     */
    public function testFromString()
    {
        // When we make an address from a string;
        $address = Address::fromString(
            <<<ADR
Fooey McBar
23 Foo Street
FooBar District
Test Town
FOO BAR
GB
ADR
        );

        // Then it should have the right attributes;
        self::assertEquals(
            [
                'name'         => 'Fooey Mcbar',
                'line_one'     => '23 Foo Street',
                'line_two'     => 'Foobar District',
                'city'         => 'Test Town',
                'post_code'    => 'FOO BAR',
                'country_code' => 'GB',
            ],
            $address->toArray()
        );
    }

    /**
     * Should apply formatting rules to the address.
     */
    public function testAddressFormatting()
    {
        // Given there is an address with poor formatting;
        $address = new Address(
            [
                'name'         => 'ms foo bar',
                'line_one'     => 'soMe house',
                'line_two'     => 'sOme street',
                'city'         => 'fooBar citY',
                'post_code'    => 'ab5 dE6',
                'country_code' => 'gb',
            ]
        );

        // When we get it as a string;
        $addressString = (string) $address;

        // Then it should be nicely formatted.
        self::assertEquals(
            'Ms Foo Bar, Some House, Some Street, Foobar City, AB5 DE6, GB',
            $addressString
        );
    }
}
