<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/** @noinspection PhpIllegalPsrClassPathInspection */
class CreateBasketsTable extends Migration
{
    const TABLE_NAME = 'baskets';

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

                // A basket may belong to a user.
                $table->integer('user_id')
                    ->unsigned()
                    ->nullable()
                    ->index();
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users');

                // A basket may point to an order.
                $table->integer('order_id')
                    ->unsigned()
                    ->unique()
                    ->nullable();
                $table->foreign('order_id')
                    ->references('id')
                    ->on('orders')
                    ->onDelete('cascade');

                /*
                 * A basket may have an address.
                 * @see \CreateAddressesTable
                 */

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
