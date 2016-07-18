<?php

namespace ChingShop\Modules\Sales\Database\Seeds;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * Class SalesDatabaseSeeder
 *
 * @package ChingShop\Modules\Sales\Database\Seeds
 */
class SalesDatabaseSeeder extends Seeder
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
