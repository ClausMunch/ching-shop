<?php

use ChingShop\Image\Image;
use ChingShop\Modules\Catalogue\Domain\Inventory\StockItem;
use ChingShop\Modules\Catalogue\Domain\Price\Price;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Catalogue\Domain\Product\ProductOption;

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

        Artisan::call('elasticsearch:index:refresh', ['-y' => true]);
    }

    /**
     * Create a fake product.
     */
    private function seedProduct()
    {
        $product = Product::create(
            [
                'name'        => ucfirst($this->faker()->words(5, true)),
                'sku'         => mb_strtoupper($this->faker()->lexify('?????')),
                'slug'        => $this->faker()->slug(),
                'description' => $this->faker()->paragraph,
            ]
        );

        $imagesIDs = [];
        foreach ($this->makeImages() as $image) {
            $imagesIDs[] = $image->id;
        }
        $product->attachImages($imagesIDs);

        $price = new Price(
            [
                'units'    => random_int(1, 100),
                'subunits' => random_int(0, 99),
                'currency' => 'GBP',
            ]
        );
        $product->prices()->save($price);

        for ($i = 0, $count = random_int(1, 3); $i < $count; $i++) {
            $this->addProductOption($product);
        }
    }

    /**
     * @return Generator|Image[]
     */
    private function makeImages(): Generator
    {
        for ($i = 0, $count = random_int(1, 2); $i < $count; $i++) {
            yield Image::create(
                [
                    'alt_text' => $this->faker()->words(3, true),
                    'url'      => secure_asset(
                        "/img/lorem/{$this->faker()->numberBetween(1, 5)}.jpg#"
                        .uniqid('', true)
                    ),
                ]
            );
        }
    }

    /**
     * @param Product $product
     */
    private function addProductOption(Product $product)
    {
        $productOption = new ProductOption(
            [
                'label' => ucfirst($this->faker()->unique()->word),
            ]
        );
        $product->options()->save($productOption);

        // Add stock items for the product option.
        $this->repeat(
            function () use ($productOption) {
                $productOption->stockItems()->save(new StockItem());
            },
            random_int(1, 3)
        );

        $imagesIDs = [];
        foreach ($this->makeImages() as $image) {
            $imagesIDs[] = $image->id;
        }
        $productOption->images()->attach($imagesIDs);
        $productOption->save();
    }
}
