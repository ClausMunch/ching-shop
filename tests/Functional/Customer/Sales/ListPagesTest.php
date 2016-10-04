<?php

namespace Testing\Functional\Customer\Sales;

use ChingShop\Modules\Catalogue\Domain\Price\Price;
use Testing\Functional\FunctionalTest;
use Testing\Functional\Util\SalesInteractions;

/**
 * Test product list page functionality.
 */
class ListPagesTest extends FunctionalTest
{
    use SalesInteractions;

    /**
     * Should be able to add a product to the basket from a list page.
     */
    public function testCanAddToBasketFromListPage()
    {
        // Given there is a product on a list page;
        $product = $this->createProduct();
        $this->createProductOptionFor($product);
        $tag = $this->createTag();
        $tag->products()->attach($product);

        // When we visit that list page;
        $this->actingAs($this->customerUser())
            ->visit(route('tag::view', [$tag->id, $tag->name]))
            ->see($product->name);

        // Then we should be able to add the product to our basket from there.
        $this->press('Add to basket')
            ->see('added')
            ->see($product->name);
    }

    /**
     * Should be able to see product prices on list pages.
     */
    public function testCanSeePriceOnListPage()
    {
        // Given there is a product on a list page;
        $product = $this->createProduct();
        $this->createProductOptionFor($product);
        $tag = $this->createTag();
        $tag->products()->attach($product);

        // And the product has a price;
        $price = $this->createPriceForProduct($product);

        // When we visit that list page;
        $this->actingAs($this->customerUser())
            ->visit(route('tag::view', [$tag->id, $tag->name]))
            ->see($product->name);

        // Then we should be able to see the price for the product.
        $this->see($price->formatted());
    }
}
