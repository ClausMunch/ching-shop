<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBasketItemsTable extends Migration
{
    const TABLE_NAME = 'basket_items';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            self::TABLE_NAME,
            function (Blueprint $table) {
                $table->increments('id');

                $table->integer('basket_id')
                    ->unsigned()
                    ->index();
                $table->foreign('basket_id')
                    ->references('id')
                    ->on('baskets')
                    ->onDelete('cascade');

                $table->integer('product_option_id')
                    ->unsigned()
                    ->index();
                $table->foreign('product_option_id')
                    ->references('id')
                    ->on('product_options')
                    ->onDelete('cascade');

                $table->timestamps();
                $table->softDeletes();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
}
