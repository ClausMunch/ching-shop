<?php

namespace Testing\Functional\Customer\StaticContent;

use Testing\Functional\FunctionalTest;
use Testing\Functional\Util\SalesInteractions;

/**
 * Test customer category view actions.
 */
class CategoriesTest extends FunctionalTest
{
    use SalesInteractions;

    /**
     * Should be able to visit the cards list.
     */
    public function testCardsPage()
    {
        $this->visit(route('customer.cards'))
            ->seeStatusCode(200)
            ->seePageIs(route('customer.cards'))
            ->see('Cards');
    }

    /**
     * Should be able to see that a product is in a category.
     */
    public function testCanSeeProductInCategory()
    {
        // Given there is a product in a category;
        $product = $this->createProduct();
        $category = $this->createCategory();
        $category->products()->save($product);

        // When we visit that product's view;
        $this->visit(route('product::view', [$product->id, $product->slug]))
            ->see($product->name)
            ->see($category->name);

        // Then we should be able to go to the category from there;
        $this->click($category->name)
            ->seePageIs(
                route(
                    'category.view',
                    [$category->publicId(), $category->slug()]
                )
            );

        // And see the product on the category page.
        $this->see($product->name)
            ->see($category->name);
    }
}
