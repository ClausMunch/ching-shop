<?php

use ChingShop\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

/** @noinspection PhpIllegalPsrClassPathInspection */
class CreateColourProductOptionPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->builder()->create('colour_product_option', function (Blueprint $table) {
            $table->integer('colour_id')
                ->unsigned()
                ->index();
            $table->foreign('colour_id')
                ->references('id')
                ->on('colours')
                ->onDelete('cascade');

            $table->integer('product_option_id')
                ->unsigned()
                ->index();
            $table->foreign('product_option_id')
                ->references('id')
                ->on('product_options')
                ->onDelete('cascade');

            $table->primary(['colour_id', 'product_option_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->builder()->drop('colour_product_option');
    }
}
