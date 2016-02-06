<?php

namespace Testing\Functional\Staff;

use Testing\Functional\FunctionalTest;

use ChingShop\User\User;
use ChingShop\Actions\MakeUser;

class ProductsTest extends FunctionalTest
{
    /** @var User */
    private $user;

    /** @var MakeUser */
    private $makeUser;

    /**
     * Set up user for dashboard testing
     */
    public function setUp()
    {
        parent::setUp();

        $email = str_random() . '@ching-shop.com';
        $password = str_random(16);
        $this->user = $this->makeUser()->make($email, $password, true);
    }

    /**
     * Should be able to load the products index page
     */
    public function testIndex()
    {
        $this->actingAs($this->user)
            ->visit(route('staff.products.index'))
            ->seePageIs(route('staff.products.index'));
    }

    /**
     * Should be able to load the create product form
     */
    public function testCreate()
    {
        $this->actingAs($this->user)
            ->visit(route('staff.products.create'))
            ->seePageIs(route('staff.products.create'))
            ->see('Create a new product');
    }

    /**
     * Should be able to store a product
     */
    public function testStoreProduct()
    {
        $productName = 'Foobar Product';
        $productSKU  = 'NICE_SKU';
        $productSlug = 'nice-slug';

        $this->actingAs($this->user)
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
     * Should give error messages if name or SKU are missing
     */
    public function testRequiredFieldErrorMessages()
    {
        $this->actingAs($this->user)
            ->visit(route('staff.products.create'))
            ->press('Save')
            ->seePageIs(route('staff.products.create'))
            ->see('The name field is required')
            ->see('The sku field is required');
    }

    /**
     * Should preserve previous input if returning to form
     */
    public function testOldInputIsPreserved()
    {
        $productName = $this->generator()->anyString();
        $productSlug = $this->generator()->anySlug();

        $this->actingAs($this->user)
            ->visit(route('staff.products.create'))
            ->type($productName, 'name')
            ->type($productSlug, 'slug')
            ->press('Save')
            ->seePageIs(route('staff.products.create'))
            ->see($productName);
    }

    /**
     * Should not be able to make a product with an existing SKU
     */
    public function testCantCreateSameSKUTwice()
    {
        $productSKU = 'NICE_SKU';
        $productSlug = $this->generator()->anySlug();

        $this->actingAs($this->user)
            ->visit(route('staff.products.create'))
            ->type($this->generator()->anyString(), 'name')
            ->type($productSKU, 'sku')
            ->type($productSlug, 'slug')
            ->press('Save')
            ->seePageIs(route('staff.products.show', ['sku' => $productSKU]));

        $this->actingAs($this->user)
            ->visit(route('staff.products.create'))
            ->type($this->generator()->anyString(), 'name')
            ->type($productSKU, 'sku')
            ->type($productSlug, 'slug')
            ->press('Save')
            ->seePageIs(route('staff.products.create'))
            ->see('The sku has already been taken');
    }

    /**
     * @return MakeUser
     */
    private function makeUser(): MakeUser
    {
        if (!isset($this->makeUser)) {
            $this->makeUser = app(MakeUser::class);
        }
        return $this->makeUser;
    }
}
