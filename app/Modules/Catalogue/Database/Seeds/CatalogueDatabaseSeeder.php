<?php

namespace ChingShop\Modules\Catalogue\Database\Seeds;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * Class CatalogueDatabaseSeeder
 *
 * @package ChingShop\Modules\Catalogue\Database\Seeds
 */
class CatalogueDatabaseSeeder extends Seeder
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
