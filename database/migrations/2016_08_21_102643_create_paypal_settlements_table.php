<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaypalSettlementsTable extends Migration
{
    const TABLE_NAME = 'paypal_settlements';

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

                $table->string('payment_id', 63)->unique();
                $table->string('payer_id');

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
