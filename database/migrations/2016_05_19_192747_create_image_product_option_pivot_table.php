<?php

use ChingShop\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

/** @noinspection PhpIllegalPsrClassPathInspection */
class CreateImageProductOptionPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->builder()->create('image_product_option', function (Blueprint $table) {
            $table->integer('image_id')
                ->unsigned()
                ->index();
            $table->foreign('image_id')
                ->references('id')
                ->on('images')
                ->onDelete('cascade');

            $table->integer('product_option_id')
                ->unsigned()
                ->index();
            $table->foreign('product_option_id')
                ->references('id')
                ->on('product_options')
                ->onDelete('cascade');

            $table->primary(['image_id', 'product_option_id']);

            $table->tinyInteger('position')
                ->default(0)
                ->unsigned()
                ->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->builder()->dropIfExists('image_product_option');
    }
}
