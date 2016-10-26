<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddProductCategory extends Migration
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
                // A product belongs to a category;
                $table->integer('category_id')
                    ->nullable()
                    ->unsigned()
                    ->index();
                $table->foreign('category_id')
                    ->references('id')
                    ->on('categories')
                    ->onDelete('set null');
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
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }
        );
    }
}
