<?php

namespace Testing\Functional\Customer\Sales;

use ChingShop\Modules\Catalogue\Domain\Price\Price;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Sales\Domain\Address;
use Testing\Functional\Customer\CustomerUsers;
use Testing\Functional\FunctionalTest;
use Testing\Functional\Util\CreateCatalogue;

trait SalesInteractions
{
    use CustomerUsers, CreateCatalogue;

    /** @var Address */
    private $address;

    /** @var Product */
    private $productInBasket;

    /**
     * @param FunctionalTest $test
     *
     * @return Product
     */
    private function createProductAndAddToBasket(FunctionalTest $test): Product
    {
        $this->productInBasket = $this->createProduct();
        $this->productInBasket->prices()->save(
            new Price(
                [
                    'units'    => random_int(1, 99),
                    'subunits' => random_int(1, 99),
                ]
            )
        );
        $this->createProductOptionFor($this->productInBasket);
        $this->addProductToBasket($this->productInBasket, $test);

        return $this->productInBasket;
    }

    /**
     * @param Product        $product
     * @param FunctionalTest $test
     */
    private function addProductToBasket(Product $product, FunctionalTest $test)
    {
        $test->actingAs($this->customerUser())
            ->visit(route('product::view', [$product->id, $product->slug]))
            ->see($product->name)
            ->press('Add to basket');
    }

    /**
     * @param FunctionalTest $test
     *
     * @return Address
     */
    private function completeCheckoutAddress(FunctionalTest $test): Address
    {
        $this->createProductAndAddToBasket($test);
        $addressName = uniqid('address-name', false);
        $test->actingAs($this->customerUser())
            ->visit(route('sales.customer.checkout.address'))
            ->type($addressName, 'name')
            ->type('42 Some Street', 'line_one')
            ->type('Some Town', 'line_two')
            ->type('London', 'city')
            ->type('FOO BAR', 'post_code')
            ->select('GB', 'country_code')
            ->press('Continue')
            ->assertResponseOk();

        $this->address = Address::where('name', '=', $addressName)->first();

        return $this->address;
    }
}
