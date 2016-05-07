<?php

namespace Testing\Functional\Util;

use ChingShop\Catalogue\Product\Product;
use ChingShop\Catalogue\Tag\Tag;

trait CreateCatalogue
{
    /**
     * @return Product
     */
    protected function createProduct(): Product
    {
        return Product::create([
            'name'        => 'Product '.str_random(),
            'sku'         => 'SKU'.uniqid(),
            'slug'        => 'slug-'.uniqid(),
            'description' => 'Description '.str_random(30),
        ]);
    }

    /**
     * @return Tag
     */
    protected function createTag(): Tag
    {
        return Tag::create([
            'name' => 'Tag'.ucfirst(str_random()),
        ]);
    }
}
