<?php

use ChingShop\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

/** @noinspection PhpIllegalPsrClassPathInspection */
class CreateImageProductPivotTable extends Migration
{
    const TABLE_NAME = 'image_product';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->builder()->create(self::TABLE_NAME, function (Blueprint $table) {
            $table->integer('image_id')->unsigned()->index();
            $table->foreign('image_id')->references('id')
                ->on('images')
                ->onDelete('cascade');

            $table->integer('product_id')->unsigned()->index();
            $table->foreign('product_id')->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->primary(['image_id', 'product_id']);
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
