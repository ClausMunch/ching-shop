<?php

namespace Testing\Functional\Staff\Products;

use Testing\Functional\FunctionalTest;

use ChingShop\Catalogue\Product\Product;

abstract class ProductTest extends FunctionalTest
{
    /**
     * @return Product
     */
    protected function makeProduct(): Product
    {
        return Product::create([
            'name' => str_random(),
            'sku'  => uniqid(),
            'slug' => uniqid(),
        ]);
    }
}
