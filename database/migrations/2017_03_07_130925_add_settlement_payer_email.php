<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Add customer_email column to orders table.
 */
class AddSettlementPayerEmail extends Migration
{
    const TABLES = [
        CreatePaypalSettlementsTable::TABLE_NAME,
        CreateStripeSettlementsTable::TABLE_NAME,
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        collect(self::TABLES)->each(
            function (string $table) {
                Schema::table(
                    $table,
                    function (Blueprint $table) {
                        $table->string('payer_email')
                            ->nullable();
                    }
                );
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
        collect(self::TABLES)->each(
            function (string $table) {
                Schema::table(
                    $table,
                    function (Blueprint $table) {
                        $table->dropColumn('payer_email');
                    }
                );
            }
        );
    }
}
