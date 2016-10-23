<?php

namespace Testing\Functional\Staff\Products;

use Testing\Functional\FunctionalTest;
use Testing\Functional\Staff\StaffUser;
use Testing\Functional\Util\CreateCatalogue;

/**
 * Test catalogue cache interactions.
 */
class CacheTest extends FunctionalTest
{
    use CreateCatalogue, StaffUser;

    /**
     * Should be able to flush the whole product cache.
     *
     * @slowThreshold 1500
     */
    public function testClearWholeProductCache()
    {
        // Given the front page products are cached;
        $this->visit('/');

        // And then a new product is created;
        $product = $this->createProduct();
        $this->createProductOptionFor($product);

        // The new product will not be visible on the front page;
        $this->visit('/')->dontSee($product->name);

        // But when we flush the whole product cache;
        $this->actingAs($this->staffUser())
            ->visit(route('products.index'))
            ->press('Clear product cache')
            ->see('Product cache was cleared');

        // Then the change should be visible on the front page.
        $this->visit('/')->see($product->name);
    }
}
