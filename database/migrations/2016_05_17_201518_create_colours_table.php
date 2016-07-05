<?php

use ChingShop\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

/** @noinspection PhpIllegalPsrClassPathInspection */
class CreateColoursTable extends Migration
{
    const TABLE_NAME = 'colours';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->builder()->create(self::TABLE_NAME, function (Blueprint $table) {
            $table->increments('id');

            $table->string('name', 63)->unique();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->builder()->drop(self::TABLE_NAME);
    }
}
