<?php

namespace Testing\Functional\Staff\Products;

use ChingShop\Catalogue\Product\Product;
use Testing\Functional\FunctionalTest;

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
