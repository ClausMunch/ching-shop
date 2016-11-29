<?php

use ChingShop\Modules\Sales\Domain\Offer\Offer;

/**
 * Generate test orders and related data.
 */
class OffersTableSeeder extends Seed
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Offer::class)->times(7)->create();
    }
}
