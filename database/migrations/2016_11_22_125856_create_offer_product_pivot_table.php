<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOfferProductPivotTable extends Migration
{
    const TABLE_NAME = 'offer_product';

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
                $table->integer('offer_id')
                    ->unsigned()
                    ->index();
                $table->foreign('offer_id')
                    ->references('id')
                    ->on('offers')
                    ->onDelete('cascade');
                $table->integer('product_id')
                    ->unsigned()
                    ->index();
                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('cascade');
                $table->primary(['offer_id', 'product_id']);
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
