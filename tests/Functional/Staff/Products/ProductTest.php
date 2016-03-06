<?php

namespace Testing\Functional\Staff\Products;

use ChingShop\Catalogue\Product\Product;
use ChingShop\Image\Image;
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

    /**
     * @param Product $product
     *
     * @return Image
     */
    protected function attachImageToProduct(Product $product): Image
    {
        $image = Image::create([
            'alt_text' => $this->generator()->anyString(),
            'url'      => $this->generator()->anySlug(),
        ]);
        $product->images()->attach($image->id);

        return $image;
    }
}
