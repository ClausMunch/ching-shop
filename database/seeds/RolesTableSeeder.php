<?php

use Illuminate\Database\Seeder;

use ChingShop\User\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => Role::STAFF]);
    }
}
