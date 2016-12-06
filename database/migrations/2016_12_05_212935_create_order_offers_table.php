<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderOffersTable extends Migration
{
    const TABLE_NAME = 'order_offers';

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

                $table->integer('order_id')
                    ->unsigned()
                    ->index();
                $table->foreign('order_id')
                    ->references('id')
                    ->on('orders')
                    ->onDelete('cascade');

                $table->integer('offer_id')
                    ->unsigned()
                    ->index()
                    ->nullable();
                $table->foreign('offer_id')
                    ->references('id')
                    ->on('offers')
                    ->onDelete('set null');

                $table->string('offer_name');
                $table->integer('amount');
                $table->integer('original_price');

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
