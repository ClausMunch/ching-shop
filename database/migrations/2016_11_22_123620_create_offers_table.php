<?php

use ChingShop\Modules\Sales\Domain\Offer\Offer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOffersTable extends Migration
{
    const TABLE_NAME = 'offers';

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

                $table->string('name', 31)->nullable();

                $table->integer('price')->nullable();
                $table->tinyInteger('percentage')->nullable();
                $table->integer('quantity')->default(1);
                $table->string('code', 31)->nullable();
                $table->enum('effect', Offer::EFFECTS);

                $table->char('colour', 6);

                $table->timestamps();
                $table->softDeletes();

                $table->unique(['colour', 'deleted_at']);
                $table->unique(
                    ['price', 'percentage', 'quantity', 'effect', 'deleted_at']
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
        Schema::dropIfExists(self::TABLE_NAME);
    }
}
