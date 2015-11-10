<?php

use Illuminate\Database\Seeder;

use ChingShop\User\Role;
use ChingShop\User\RoleResource;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RoleResource::create(['name' => Role::STAFF]);
    }
}
