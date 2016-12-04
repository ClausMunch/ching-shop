<?php

use ChingShop\Domain\Colour;
use ChingShop\Modules\Sales\Domain\Offer\Offer;
use Faker\Generator;

$priced = function (Generator $faker) {
    $price = $faker->numberBetween(1, 20) * 100 ?? null;

    return [
        'price'    => $price,
        'quantity' => $faker->numberBetween(1, 5),
        'colour'   => Colour::fromHex(
            $faker->hexColor
        )->mix(Colour::brand())->pastel()->toHex(),
        'effect'   => $faker->randomElement(Offer::EFFECTS),
    ];
};

$percentage = function (Generator $faker) {
    $percentage = $faker->numberBetween(1, 19) * 5 ?? null;

    return [
        'percentage' => $percentage,
        'quantity'   => $faker->numberBetween(1, 5),
        'colour'     => Colour::fromHex(
            $faker->hexColor
        )->mix(Colour::brand())->pastel()->toHex(),
        'effect'     => $faker->randomElement(Offer::EFFECTS),
    ];
};

/* @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(
    Offer::class,
    function (Generator $faker) use ($priced, $percentage) {
        if ($faker->boolean()) {
            return $priced($faker);
        }

        return $percentage($faker);
    }
);

$factory->state(Offer::class, 'price', $priced);
$factory->state(Offer::class, 'percentage', $percentage);
