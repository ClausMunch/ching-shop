<?php

namespace Testing\Customer;

use Testing\Functional\FunctionalTest;
use Testing\Functional\Util\CreateCatalogue;

/**
 * Test basic product search functionality.
 */
class SearchTest extends FunctionalTest
{
    use CreateCatalogue;

    /**
     * Should be able to find a product in search results.
     */
    public function testSearchForProduct()
    {
        // Given there is a product;
        $product = $this->createProduct();

        // When we search for the product's name;
        $this->visit('/')
            ->type($product->name, 'q')
            ->press('Search');

        // Then we should see the product in the search results.
        $this->see($product->name);
        $this->see($product->description);
        $this->click($product->name)->seePageIs($product->url());
    }

    /**
     * Product search must be exclusive to be useful.
     */
    public function testSearchExclusive()
    {
        // Given there is a product with one name;
        $product = $this->createProduct();

        // And another product with a different name;
        $otherProduct = $this->createProduct(['name' => uniqid('', false)]);

        // When we search for the first product's name;
        $this->visit('/')
            ->type($product->name, 'q')
            ->press('Search');

        // Then we should see the first product;
        $this->see($product->description);

        // And we should not see the other product.
        $this->dontSee($otherProduct->name);
        $this->dontSee($otherProduct->description);
    }
}
