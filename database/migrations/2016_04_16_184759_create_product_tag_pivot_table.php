<?php

use ChingShop\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

/** @noinspection PhpIllegalPsrClassPathInspection */
class CreateProductTagPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->builder()->create('product_tag', function (Blueprint $table) {
            $table->integer('product_id')
                ->unsigned()
                ->index();
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->integer('tag_id')
                ->unsigned()
                ->index();
            $table->foreign('tag_id')
                ->references('id')
                ->on('tags')
                ->onDelete('cascade');

            $table->primary(['product_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->builder()->dropIfExists('product_tag');
    }
}
