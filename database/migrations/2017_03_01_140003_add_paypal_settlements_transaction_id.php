<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class AddPaypalSettlementsTransactionId
 */
class AddPaypalSettlementsTransactionId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            CreatePaypalSettlementsTable::TABLE_NAME,
            function (Blueprint $table) {
                $table->string('transaction_id', 63)
                    ->nullable()
                    ->after('payment_id');
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
            CreatePaypalSettlementsTable::TABLE_NAME,
            function (Blueprint $table) {
                $table->dropColumn('transaction_id');
            }
        );
    }
}
