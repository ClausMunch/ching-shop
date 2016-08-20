<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            CreateOrderItemsTable::TABLE_NAME,
            function (Blueprint $table) {

                // An order item references a basket item;
                $table->integer('basket_item_id')
                    ->nullable()
                    ->unsigned()
                    ->index();
                $table->foreign('basket_item_id')
                    ->references('id')
                    ->on('basket_items');
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
        Schema::table(
            CreateOrderItemsTable::TABLE_NAME,
            function (Blueprint $table) {
                $table->dropForeign(['basket_item_id']);
                $table->dropColumn('basket_item_id');
            }
        );
    }
}
