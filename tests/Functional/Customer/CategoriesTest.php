<?php

namespace Testing\Functional\Customer\StaticContent;

use Testing\Functional\FunctionalTest;

class CategoriesTest extends FunctionalTest
{
    /**
     * Should be able to visit the cards list
     */
    public function testCardsPage()
    {
        $this->visit(route('customer.cards'))
            ->seeStatusCode(200)
            ->seePageIs(route('customer.cards'))
            ->see('Cards');
    }
}
