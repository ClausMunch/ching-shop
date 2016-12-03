<?php

/*
|--------------------------------------------------------------------------
| Domain Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Domain factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(
    ChingShop\Modules\User\Model\User::class,
    function (Faker\Generator $faker) {
        return [
            'name'           => $faker->name,
            'email'          => str_random().'@test.ching-shop.dev',
            'password'       => bcrypt(str_random(10)),
            'remember_token' => str_random(10),
        ];
    }
);

$factory->define(
    \ChingShop\Modules\Catalogue\Domain\Category::class,
    function (\Faker\Generator $faker) {
        return [
            'name' => ucwords($faker->unique()->words(random_int(2, 3), true)),
        ];
    }
);

$factory->define(
    \ChingShop\Modules\Catalogue\Domain\Inventory\StockItem::class,
    function () {
        return [];
    }
);

$factory->define(
    \ChingShop\Modules\Sales\Domain\Basket\BasketItem::class,
    function () {
        return [];
    }
);

