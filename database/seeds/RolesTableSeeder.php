<?php

use ChingShop\Modules\User\Model\Role;
use ChingShop\Modules\User\Model\User;

class RolesTableSeeder extends Seed
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $staff = Role::create(['name' => Role::STAFF]);

        $developer = User::create([
            'name'     => 'Developer',
            'email'    => 'developer@ching-shop.com',
            'password' => bcrypt('developer'),
        ]);

        $developer->roles()->sync([$staff->id]);
        $developer->save();
    }
}
