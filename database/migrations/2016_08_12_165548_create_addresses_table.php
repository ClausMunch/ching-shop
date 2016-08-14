<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
                $table->string('post_code');
                $table->string('country')->default('United Kingdom');

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
