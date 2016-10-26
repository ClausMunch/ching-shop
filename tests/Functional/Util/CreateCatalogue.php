<?php

namespace Testing\Functional\Util;

use ChingShop\Image\Image;
use ChingShop\Modules\Catalogue\Domain\Attribute\Colour;
use ChingShop\Modules\Catalogue\Domain\Category;
use ChingShop\Modules\Catalogue\Domain\Inventory\StockItem;
use ChingShop\Modules\Catalogue\Domain\Price\Price;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Catalogue\Domain\Product\ProductOption;
use ChingShop\Modules\Catalogue\Domain\Tag\Tag;
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
     * @param array $attributes
     *
     * @return Product
     */
    protected function createProduct(array $attributes = []): Product
    {
        return Product::create(
            array_merge(
                [
                    'name'        => uniqid('', false),
                    'sku'         => uniqid('SKU', false),
                    'slug'        => uniqid('slug-', false),
                    'description' => 'Description '.str_random(30),
                ],
                $attributes
            )
        );
    }

    /**
     * @return Tag
     */
    protected function createTag(): Tag
    {
        return Tag::create(
            [
                'name' => uniqid('Tag', false),
            ]
        );
    }

    /**
     * @return Category
     */
    protected function createCategory(): Category
    {
        return Category::create(
            [
                'name' => uniqid('Category', false),
            ]
        );
    }

    /**
     * @param Product $product
     *
     * @return ProductOption
     */
    protected function createProductOptionFor(Product $product): ProductOption
    {
        $productOption = new ProductOption(
            [
                'label' => uniqid('ProductOption', false),
            ]
        );
        $product->options()->save($productOption);
        $productOption->stockItems()->save(new StockItem());

        return $productOption;
    }

    /**
     * @param Product $product
     *
     * @return Price
     */
    protected function createPriceForProduct(Product $product): Price
    {
        $price = new Price(
            [
                'units'    => random_int(1, 99),
                'subunits' => random_int(0, 99),
            ]
        );
        $product->prices()->save($price);

        return $price;
    }

    /**
     * @return Colour
     */
    protected function createColour(): Colour
    {
        return Colour::create(
            [
                'name' => uniqid($this->faker()->unique()->colorName, false),
            ]
        );
    }

    /**
     * @return Image
     */
    protected function createImage(): Image
    {
        return Image::create(
            [
                'alt_text' => str_random(),
                'url'      => $this->faker()->slug,
            ]
        );
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
