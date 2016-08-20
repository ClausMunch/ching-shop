<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaypalInitiationsTable extends Migration
{
    const TABLE_NAME = 'paypal_initiations';

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

                $table->integer('basket_id')
                    ->unsigned()
                    ->index();
                $table->foreign('basket_id')
                    ->references('id')
                    ->on('baskets')
                    ->onDelete('cascade');

                $table->decimal('amount');

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
