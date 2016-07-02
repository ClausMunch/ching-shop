<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateColourProductOptionPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colour_product_option', function (Blueprint $table) {
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
        Schema::drop('colour_product_option');
    }
}
