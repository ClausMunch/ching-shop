<?php

namespace Testing\Functional\Staff\Products;

use ChingShop\Modules\Catalogue\Domain\Inventory\StockItem;
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
            ->visit(route('products.index'))
            ->seePageIs(route('products.index'));
    }

    /**
     * Should be able to load the create product form.
     */
    public function testCreate()
    {
        $this->actingAs($this->staffUser())
            ->visit(route('products.create'))
            ->seePageIs(route('products.create'))
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
        $productDescription = 'foobar nice description of the product';

        $this->actingAs($this->staffUser())
            ->visit(route('products.create'))
            ->type($productName, 'name')
            ->type($productDescription, 'description')
            ->type($productSKU, 'sku')
            ->type($productSlug, 'slug')
            ->press('Save')
            ->seePageIs(route('products.show', ['SKU' => $productSKU]))
            ->see($productName)
            ->see($productSKU);
    }

    /**
     * Should be able to go to a show product page.
     */
    public function testShowProduct()
    {
        $product = $this->createProduct();
        $showRoute = route(
            'products.show',
            [
                'sku' => $product->sku,
            ]
        );

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
            ->visit(route('products.create'))
            ->press('Save')
            ->seePageIs(route('products.create'))
            ->see('The name field is required')
            ->see('The description field is required')
            ->see('The sku field is required');
    }

    /**
     * Should show an error message if the slug is too short.
     */
    public function testSlugFieldLengthErrorMessage()
    {
        $this->actingAs($this->staffUser())
            ->visit(route('products.create'))
            ->type($this->generator()->anyString(), 'name')
            ->type($this->generator()->anySlug(), 'sku')
            ->type('1234', 'slug')
            ->press('Save')
            ->seePageIs(route('products.create'))
            ->see('The slug must be at least 5 characters.');
    }

    /**
     * Should preserve previous input if returning to form.
     */
    public function testOldInputIsPreserved()
    {
        $productName = $this->generator()->anyString();
        $productSlug = $this->generator()->anySlug();
        $productDescription = 'foobar nice description of the product';

        $this->actingAs($this->staffUser())
            ->visit(route('products.create'))
            ->type($productName, 'name')
            ->type($productSlug, 'slug')
            ->type($productDescription, 'description')
            ->press('Save')
            ->seePageIs(route('products.create'))
            ->see($productName)
            ->see($productSlug)
            ->see($productDescription);
    }

    /**
     * Should not be able to make a product with an existing SKU.
     */
    public function testCantCreateSameSKUTwice()
    {
        $productSKU = 'NICE_SKU';
        $productSlug = $this->generator()->anySlug();
        $productDescription = 'foobar nice description of the product';

        $this->actingAs($this->staffUser())
            ->visit(route('products.create'))
            ->type($this->generator()->anyString(), 'name')
            ->type($productSKU, 'sku')
            ->type($productSlug, 'slug')
            ->type($productDescription, 'description')
            ->press('Save')
            ->dontSee('already been taken')
            ->seePageIs(route('products.show', ['sku' => $productSKU]));

        $this->actingAs($this->staffUser())
            ->visit(route('products.create'))
            ->type($this->generator()->anyString(), 'name')
            ->type($productSKU, 'sku')
            ->type($productSlug, 'slug')
            ->type($productDescription, 'description')
            ->press('Save')
            ->seePageIs(route('products.create'))
            ->see('The sku has already been taken');
    }

    /**
     * Should be able to edit an existing product.
     */
    public function testCanEditProduct()
    {
        $product = $this->createProduct();

        $oldProductName = $product->name;
        $newProductName = str_random();
        $this->actingAs($this->staffUser())
            ->visit(route('products.show', [$product->sku]))
            ->click('Edit product')
            ->type($newProductName, 'name')
            ->press('Save')
            ->see($newProductName)
            ->dontSee($oldProductName);
    }

    /**
     * Should be able to see products with no stock in the staff area.
     */
    public function testCanSeeOutOfStockProduct()
    {
        // Given there is a product with no stock;
        $product = $this->createProduct();
        $option = $this->createProductOptionFor($product);
        $option->stockItems->each(
            function (StockItem $stock) {
                $stock->delete();
            }
        );

        // When we go to the staff products index;
        $this->actingAs($this->staffUser())
            ->visit(route('products.index'));

        // Then we should see that product.
        $this->see($product->name);
        $this->see($product->sku);
    }
}
