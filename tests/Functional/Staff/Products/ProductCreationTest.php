<?php

namespace Testing\Functional\Staff\Products;

use Testing\Functional\Staff\StaffUser;

class ProductCreationTest extends ProductTest
{
    use StaffUser;

    /**
     * Should be able to load the products index page.
     */
    public function testIndex()
    {
        $this->actingAs($this->staffUser())
            ->visit(route('staff.products.index'))
            ->seePageIs(route('staff.products.index'));
    }

    /**
     * Should be able to load the create product form.
     */
    public function testCreate()
    {
        $this->actingAs($this->staffUser())
            ->visit(route('staff.products.create'))
            ->seePageIs(route('staff.products.create'))
            ->see('Create a new product');
    }

    /**
     * Should be able to store a product.
     */
    public function testStoreProduct()
    {
        $productName = 'Foobar Product';
        $productSKU = 'NICE_SKU';
        $productSlug = 'nice-slug';

        $this->actingAs($this->staffUser())
            ->visit(route('staff.products.create'))
            ->type($productName, 'name')
            ->type($productSKU, 'sku')
            ->type($productSlug, 'slug')
            ->press('Save')
            ->seePageIs(route('staff.products.show', ['SKU' => $productSKU]))
            ->see($productName)
            ->see($productSKU);
    }

    /**
     * Should be able to go to a show product page.
     */
    public function testShowProduct()
    {
        $product = $this->makeProduct();
        $showRoute = route('staff.products.show', [
            'sku' => $product->sku
        ]);

        $this->actingAs($this->staffUser())
            ->visit($showRoute)
            ->seePageIs($showRoute)
            ->see($product->sku)
            ->see($product->name);

        $response = $this->call('GET', $showRoute);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Should give error messages if name or SKU are missing.
     */
    public function testRequiredFieldErrorMessages()
    {
        $this->actingAs($this->staffUser())
            ->visit(route('staff.products.create'))
            ->press('Save')
            ->seePageIs(route('staff.products.create'))
            ->see('The name field is required')
            ->see('The sku field is required');
    }

    /**
     * Should preserve previous input if returning to form.
     */
    public function testOldInputIsPreserved()
    {
        $productName = $this->generator()->anyString();
        $productSlug = $this->generator()->anySlug();

        $this->actingAs($this->staffUser())
            ->visit(route('staff.products.create'))
            ->type($productName, 'name')
            ->type($productSlug, 'slug')
            ->press('Save')
            ->seePageIs(route('staff.products.create'))
            ->see($productName);
    }

    /**
     * Should not be able to make a product with an existing SKU.
     */
    public function testCantCreateSameSKUTwice()
    {
        $productSKU = 'NICE_SKU';
        $productSlug = $this->generator()->anySlug();

        $this->actingAs($this->staffUser())
            ->visit(route('staff.products.create'))
            ->type($this->generator()->anyString(), 'name')
            ->type($productSKU, 'sku')
            ->type($productSlug, 'slug')
            ->press('Save')
            ->seePageIs(route('staff.products.show', ['sku' => $productSKU]));

        $this->actingAs($this->staffUser())
            ->visit(route('staff.products.create'))
            ->type($this->generator()->anyString(), 'name')
            ->type($productSKU, 'sku')
            ->type($productSlug, 'slug')
            ->press('Save')
            ->seePageIs(route('staff.products.create'))
            ->see('The sku has already been taken');
    }
}
