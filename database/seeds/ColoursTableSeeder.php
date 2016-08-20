<?php

use ChingShop\Modules\Catalogue\Domain\Attribute\Colour;
use ChingShop\Modules\Catalogue\Domain\Product\ProductOption;

class ColoursTableSeeder extends Seed
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var Colour[] $colours */
        $colours = [];

        for ($i = 0; $i < 8; $i++) {
            $colours[] = Colour::create([
                'name' => $this->faker()->unique()->colorName,
            ]);
        }

        /** @var ProductOption $productOption */
        foreach (ProductOption::all() as $productOption) {
            $productOption->colours()->sync([
                $this->faker()->randomElement($colours)->id,
            ]);
        }
    }
}
