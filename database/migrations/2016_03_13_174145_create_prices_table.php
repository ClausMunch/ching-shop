<?php

use ChingShop\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

/** @noinspection PhpIllegalPsrClassPathInspection */
class CreatePricesTable extends Migration
{
    const TABLE_NAME = 'prices';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->builder()->create(self::TABLE_NAME, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('units');
            $table->integer('subunits');
            $table->char('currency', 3)->default('GBP');
            $table->timestamps();
            $table->softDeletes();

            $table->integer('product_id')->unsigned()->index();
            $table->foreign('product_id')->references('id')
                ->on('products')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->builder()->dropIfExists(self::TABLE_NAME);
    }
}
