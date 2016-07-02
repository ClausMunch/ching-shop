<?php

use ChingShop\Catalogue\Price\Price;
use ChingShop\Catalogue\Product\Product;
use ChingShop\Catalogue\Product\ProductOption;
use ChingShop\Image\Image;

class ProductsTableSeeder extends Seed
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 8; ++$i) {
            $this->seedProduct();
        }
    }

    /**
     * Create a fake product.
     */
    private function seedProduct()
    {
        $product = Product::create([
            'name'        => ucfirst($this->faker()->words(5, true)),
            'sku'         => mb_strtoupper($this->faker()->lexify('?????')),
            'slug'        => $this->faker()->slug(),
            'description' => $this->faker()->paragraph,
        ]);

        $imagesIDs = [];
        foreach ($this->makeImages() as $image) {
            $imagesIDs[] = $image->id;
        }
        $product->attachImages($imagesIDs);

        $price = new Price([
            'units'    => mt_rand(1, 100),
            'subunits' => mt_rand(0, 99),
            'currency' => 'GBP',
        ]);
        $product->prices()->save($price);

        for ($i = 0; $i < rand(1, 3); $i++) {
            $this->addProductOption($product);
        }
    }

    /**
     * @return Generator|Image[]
     */
    private function makeImages(): Generator
    {
        for ($i = 0; $i < rand(2, 3); $i++) {
            yield Image::create([
                'alt_text' => $this->faker()->words(3, true),
                'url'      => secure_asset(
                    "/img/lorem/{$this->faker()->numberBetween(1, 5)}.jpg#"
                    .uniqid()
                ),
            ]);
        }
    }

    /**
     * @param Product $product
     */
    private function addProductOption(Product $product)
    {
        $productOption = new ProductOption([
            'label' => ucfirst($this->faker()->unique()->word),
        ]);
        $product->options()->save($productOption);

        $imagesIDs = [];
        foreach ($this->makeImages() as $image) {
            $imagesIDs[] = $image->id;
        }
        $productOption->images()->attach($imagesIDs);
        $productOption->save();
    }
}
