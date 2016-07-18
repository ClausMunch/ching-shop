<?php

namespace Testing\Functional\Util;

use ChingShop\Image\Image;
use ChingShop\Modules\Catalogue\Model\Attribute\Colour;
use ChingShop\Modules\Catalogue\Model\Product\Product;
use ChingShop\Modules\Catalogue\Model\Product\ProductOption;
use ChingShop\Modules\Catalogue\Model\Tag\Tag;
use Faker\Factory;
use Faker\Generator;

/**
 * Class CreateCatalogue.
 */
trait CreateCatalogue
{
    /** @var Generator */
    private $faker;

    /**
     * @return Product
     */
    protected function createProduct(): Product
    {
        return Product::create([
            'name'        => 'Product '.str_random(),
            'sku'         => 'SKU'.str_random(),
            'slug'        => 'slug-'.str_random(),
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

    /**
     * @param Product $product
     *
     * @return ProductOption
     */
    protected function createProductOptionFor(Product $product): ProductOption
    {
        $productOption = new ProductOption([
            'label' => 'ProductOption'.ucfirst(str_random()),
        ]);
        $product->options()->save($productOption);

        return $productOption;
    }

    /**
     * @return Colour
     */
    protected function createColour(): Colour
    {
        return Colour::create([
            'name' => uniqid($this->faker()->unique()->colorName, false),
        ]);
    }

    /**
     * @return Image
     */
    protected function createImage(): Image
    {
        return Image::create([
            'alt_text' => str_random(),
            'url'      => $this->faker()->slug,
        ]);
    }

    /**
     * @return Generator
     */
    private function faker(): Generator
    {
        if ($this->faker === null) {
            $this->faker = Factory::create();
        }

        return $this->faker;
    }
}
