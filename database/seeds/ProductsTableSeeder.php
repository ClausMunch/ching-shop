<?php

use ChingShop\Catalogue\Product\Product;
use ChingShop\Image\Image;

class ProductsTableSeeder extends Seed
{
    const IMAGE_URL = 'http://lorempixel.com/800/600/abstract';

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
    }

    /**
     * @return Generator|Image[]
     */
    private function makeImages(): Generator
    {
        $count = mt_rand(5, 8);
        for ($i = 0; $i < $count; $i++) {
            yield Image::create([
                'url'      => sprintf(
                    '%s/%s/%s',
                    self::IMAGE_URL,
                    $this->faker()->numberBetween(0, 10),
                    $this->faker()->unique()->word
                ),
                'alt_text' => $this->faker()->words(3, true),
            ]);
        }
    }
}
