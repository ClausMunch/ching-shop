<?php

namespace Testing\Functional\Customer\StaticContent;

use Testing\Functional\FunctionalTest;

class StaticViewsTest extends FunctionalTest
{
    /**
     * Should be able to visit the home page!
     */
    public function testHome()
    {
        $this->visit('/')
            ->seeStatusCode(200)
            ->see('Ching Shop');
    }

    /**
     * Should be able to visit About page
     */
    public function testAboutPage()
    {
        $this->visit(route('customer.static', ['slug' => 'about']))
            ->seeStatusCode(200)
            ->seePageIs(route('customer.static', ['slug' => 'about']))
            ->see('About');
    }

    /**
     * Should be able to visit Contact page
     */
    public function testContactPage()
    {
        $this->visit(route('customer.static', ['slug' => 'contact']))
            ->seeStatusCode(200)
            ->seePageIs(route('customer.static', ['slug' => 'contact']))
            ->see('Contact');
    }

    /**
     * Should get 404 for unknown path
     */
    public function test404()
    {
        $this->call('GET', route('customer.static', ['slug' => 'bad-path']));
        $this->seeStatusCode(404);
    }
}
