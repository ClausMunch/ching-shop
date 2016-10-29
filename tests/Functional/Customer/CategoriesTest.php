<?php

namespace Testing\Functional\Customer\StaticContent;

use ChingShop\Modules\Catalogue\Domain\Category;
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

    /**
     * A category page should contain that category's products and the products
     * of all of its child categories.
     */
    public function testCanSeeSubCategoryProducts()
    {
        // Given there is a category with a sub category;
        $parentCategory = $this->createCategory();
        /** @var Category $childCategory */
        $childCategory = $this->createCategory()->makeChildOf($parentCategory);

        // And there is one product in each;
        $childProduct = $this->createProduct();
        $childCategory->products()->save($childProduct);
        $parentProduct = $this->createProduct();
        $parentCategory->products()->save($parentProduct);

        // When we view the child category, we should see only its product;
        $this->visit($childCategory->url())
            ->see($childCategory->name)
            ->see($childProduct->name)
            ->dontSee($parentProduct->name);

        // And when we view the parent category, we should see its product and
        // the child category's product.
        $this->visit($parentCategory->url())
            ->see($parentCategory->name)
            ->see($childProduct->name)
            ->see($parentProduct->name);
    }
}
