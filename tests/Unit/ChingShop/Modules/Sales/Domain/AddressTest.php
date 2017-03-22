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
        $this->assertEquals(
            'Ms Foo Bar, Some House, Some Street, Foobar City, AB5 DE6, GB',
            $addressString
        );
    }
}
