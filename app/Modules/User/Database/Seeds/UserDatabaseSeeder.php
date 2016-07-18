<?php

namespace ChingShop\Modules\User\Database\Seeds;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * Class UserDatabaseSeeder
 *
 * @package ChingShop\Modules\User\Database\Seeds
 */
class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
    }
}
