<?php

namespace Testing\Functional\Staff\Products;

use Testing\Functional\Staff\StaffUser;

class ProductPriceTest extends ProductTest
{
    use StaffUser;

    /**
     * Should be able to set the price for a product.
     */
    public function testCanSetPriceOnProduct()
    {
        $product = $this->createProduct();

        $showProductRoute = route(
            'products.show',
            [
                'SKU' => $product->sku,
            ]
        );
        $this->actingAs($this->staffUser())
            ->visit($showProductRoute)
            ->see($product->sku)
            ->type(5, 'units')
            ->type(99, 'subunits')
            ->press('Set price')
            ->seePageIs($showProductRoute)
            ->see(5)
            ->see(99);
    }

    /**
     * Should be able to see a price on the customer site.
     */
    public function testCanSeePriceOnCustomerSite()
    {
        $product = $this->createProduct();

        $showProductRoute = route(
            'products.show',
            [
                'SKU' => $product->sku,
            ]
        );
        $this->actingAs($this->staffUser())
            ->visit($showProductRoute)
            ->see($product->sku)
            ->type(3, 'units')
            ->type(5, 'subunits')
            ->press('Set price');

        $viewProductRoute = route(
            'product::view',
            [
                'id'  => $product->id,
                'sku' => $product->sku,
            ]
        );
        $this->visit($viewProductRoute)
            ->see($product->sku)
            ->see($product->name)
            ->see('£3.05');
    }
}
