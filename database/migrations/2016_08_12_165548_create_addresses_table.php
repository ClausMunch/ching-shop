<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAddressesTable extends Migration
{
    const TABLE_NAME = 'addresses';
    const ADDRESSABLE = ['baskets', 'orders'];

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

                $table->string('name');

                $table->string('line_one');
                $table->string('line_two')->nullable();
                $table->string('city');
                $table->string('post_code');
                $table->string('country_code')->default('GB');

                $table->timestamps();
                $table->softDeletes();
            }
        );

        // Add address column to addressable tables.
        foreach (self::ADDRESSABLE as $addressable) {
            Schema::table(
                $addressable,
                function (Blueprint $table) {
                    $table->integer('address_id')
                        ->unsigned()
                        ->nullable()
                        ->index();
                    $table->foreign('address_id')
                        ->references('id')
                        ->on('addresses');
                }
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (self::ADDRESSABLE as $addressable) {
            Schema::table(
                $addressable,
                function (Blueprint $table) {
                    $table->dropForeign(['address_id']);
                    $table->dropColumn('address_id');
                }
            );
        }

        Schema::dropIfExists(self::TABLE_NAME);
    }
}
