<?php

namespace Testing\Functional\Staff\Categories;

use Testing\Functional\FunctionalTest;
use Testing\Functional\Staff\StaffUser;
use Testing\Functional\Util\CreateCatalogue;

/**
 * Test staff category interactions.
 */
class CategoryTest extends FunctionalTest
{
    use StaffUser, CreateCatalogue;

    /**
     * Should be able to visit the staff category index.
     */
    public function testCanViewCategoryIndex()
    {
        $this->actingAs($this->staffUser())
            ->visit(route('categories.index'))
            ->assertResponseOk()
            ->seePageIs(route('categories.index'));
    }

    /**
     * Non-staff users should not be able to access the category index.
     */
    public function testNonStaffCantViewCategoryIndex()
    {
        $this->visit(route('categories.index'))
            ->seePageIs(route('login'));
    }

    /**
     * Should be able to create a new category.
     */
    public function testCanCreateNewCategory()
    {
        $this->actingAs($this->staffUser())
            ->visit(route('categories.index'))
            ->type('Foobar', 'name')
            ->press('Add new category')
            ->see('created')
            ->see('Foobar');
    }

    /**
     * Should be able to delete a category.
     */
    public function testCanDeleteCategory()
    {
        $this->actingAs($this->staffUser())
            ->visit(route('categories.index'))
            ->type('Foobar', 'name')
            ->press('Add new category')
            ->press('Delete')
            ->see('deleted');
    }

    /**
     * Should be able to set the category for a product.
     */
    public function testCanSetProductCategory()
    {
        // Given there is a product and a category;
        $product = $this->createProduct();
        $category = $this->createCategory();

        // When we go to the staff view for that product;
        $this->actingAs($this->staffUser())
            ->visit(route('products.show', [$product->sku]));

        // And set the category for it;
        $this->select($category->id, 'category-id')
            ->press('Set category');

        // Then that category should be set for the product.
        $this->see('Set category')
            ->see($product->name)
            ->see($category->name);

        $categoryOption = $this->crawler()->filter(
            "#category-option-{$category->id}"
        );
        $this->assertEquals($categoryOption->attr('selected'), 'selected');
    }
}
