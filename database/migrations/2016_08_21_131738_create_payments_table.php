<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    const TABLE_NAME = 'payments';

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

                // A payment is for an order.
                $table->integer('order_id')
                    ->unsigned()
                    ->index();
                $table->foreign('order_id')
                    ->references('id')
                    ->on('orders');

                // A payment points to a settlement.
                $table->morphs('settlement');

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
