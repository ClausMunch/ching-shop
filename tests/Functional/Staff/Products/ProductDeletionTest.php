<?php

namespace Testing\Functional\Staff\Products;

use ChingShop\Catalogue\Product\Product;
use Testing\Functional\Staff\StaffUser;

class ProductDeletionTest extends ProductTest
{
    use StaffUser;

    /**
     * Should see the delete button on the show product view.
     */
    public function testCanSeeDeleteButtonOnProductView()
    {
        $product = $this->makeProduct();

        $this->actingAs($this->staffUser())
            ->visit(route('staff.products.show', ['SKU' => $product->sku]))
            ->see($product->sku)
            ->see('Delete');

        $deleteForm = $this->crawler->filter('#delete-product-form')->first();

        $this->assertEquals(
            route('staff.products.destroy', ['sku' => $product->sku]),
            $deleteForm->attr('action')
        );
    }

    /**
     * Pressing the delete button should delete the product.
     */
    public function testDeleteButtonDeletesProduct()
    {
        $product = $this->makeProduct();

        $this->actingAs($this->staffUser())
            ->visit(route('staff.products.show', ['SKU' => $product->sku]))
            ->press('Delete')
            ->seePageIs(route('staff.products.index'));

        /** @var Product $deletedProduct */
        $deletedProduct = Product::onlyTrashed()
            ->where('id', '=', $product->id)
            ->first();

        $this->assertEquals($product->id, $deletedProduct->id);
        $this->assertTrue($deletedProduct->trashed());
    }
}
