<?php

namespace Testing\Functional\Staff\Products;

use ChingShop\Modules\Catalogue\Domain\Inventory\StockItem;
use Testing\Functional\FunctionalTest;
use Testing\Functional\Staff\StaffUser;
use Testing\Functional\Util\CreateCatalogue;

/**
 * Test staff stock-keeping functionality.
 */
class StockTest extends FunctionalTest
{
    use StaffUser, CreateCatalogue;

    /**
     * Should be able to see stock quantity.
     */
    public function testCanSeeStock()
    {
        // Given there is a product with an option with one stock item;
        $product = $this->createProduct();
        $option = $this->createProductOptionFor($product); // Adds 1 stock.

        // When we view the product in the staff area;
        $this->actingAs($this->staffUser())
            ->visit(route('products.show', [$product->sku]))
            ->see($product->name)
            ->see($option->label);

        // Then we should see the stock count.
        $this->assertEquals(
            1,
            $this->getElementAttribute("#option-{$option->id}-stock", 'value')
        );
    }

    /**
     * Should be able to increase the stock quantity.
     */
    public function testIncreaseStock()
    {
        // Given there is a product with an option with one stock item;
        $product = $this->createProduct();
        $option = $this->createProductOptionFor($product); // Adds 1 stock.

        // When we increase the stock to 8;
        $this->actingAs($this->staffUser())
            ->visit(route('products.show', [$product->sku]))
            ->type(8, 'quantity')
            ->press('Save stock');

        // Then the stock should be 8.
        $this->see('Added 7 stock');
        $this->assertEquals(
            8,
            $this->getElementAttribute("#option-{$option->id}-stock", 'value')
        );
    }

    /**
     * Should be able to decrease the stock quantity.
     */
    public function testDecreaseStock()
    {
        // Given there is a product with an option with five stock items;
        $product = $this->createProduct();
        $option = $this->createProductOptionFor($product); // Adds 1 stock.
        for ($i = 0; $i < 4; $i++) {
            $option->stockItems()->save(new StockItem());
        }

        // When we decrease the stock to 2;
        $this->actingAs($this->staffUser())
            ->visit(route('products.show', [$product->sku]))
            ->type(2, 'quantity')
            ->press('Save stock');

        // Then the stock should be 2.
        $this->see('Removed 3 stock');
        $this->assertEquals(
            2,
            $this->getElementAttribute("#option-{$option->id}-stock", 'value')
        );
    }
}
