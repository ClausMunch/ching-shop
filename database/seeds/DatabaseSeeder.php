<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(RolesTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(ColoursTableSeeder::class);
        $this->call(OrdersTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(OffersTableSeeder::class);

        Model::reguard();
    }
}
