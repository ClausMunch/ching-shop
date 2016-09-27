<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration
{
    const TABLE_NAME = 'orders';

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

                // An order belongs to a user.
                $table->integer('user_id')
                    ->unsigned()
                    ->nullable()
                    ->index();
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users');

                /*
                 * An order may have an address.
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
