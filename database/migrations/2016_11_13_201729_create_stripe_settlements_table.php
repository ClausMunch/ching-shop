<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateStripeSettlementsTable.
 */
class CreateStripeSettlementsTable extends Migration
{
    const TABLE_NAME = 'stripe_settlements';

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

                $table->string('token');

                $table->string('stripe_id')->nullable();
                $table->integer('amount')->nullable();
                $table->string('balance_transaction')->nullable();
                $table->boolean('captured')->nullable();
                $table->integer('created')->nullable();
                $table->string('currency')->nullable();
                $table->string('description')->nullable();
                $table->string('failure_code')->nullable();
                $table->string('failure_message')->nullable();
                $table->boolean('paid')->nullable();
                $table->string('status')->nullable();

                $table->string('address_zip')->nullable();
                $table->string('name')->nullable();

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
