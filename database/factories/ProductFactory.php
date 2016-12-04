<?php

/** @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(
    \ChingShop\Modules\Catalogue\Domain\Product\Product::class,
    function (Faker\Generator $faker) {
        return [
            'name'            => $faker->words(5, true),
            'sku'             => strtoupper(implode('_', $faker->words)),
            'slug'            => $faker->slug,
            'description'     => $faker->paragraph,
            'supplier_number' => $faker->numberBetween(999, 99999),
        ];
    }
);
