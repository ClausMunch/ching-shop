<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddSupplierNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            CreateProductsTable::TABLE_NAME,
            function (Blueprint $table) {
                $table->string('supplier_number', 63)->nullable();
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
            CreateProductsTable::TABLE_NAME,
            function (Blueprint $table) {
                $table->dropColumn('supplier_number');
            }
        );
    }
}
