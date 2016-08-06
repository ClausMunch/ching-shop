<?php

use ChingShop\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

/** @noinspection PhpIllegalPsrClassPathInspection */
class CreateTagsTable extends Migration
{
    const TABLE_NAME = 'tags';

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
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE `tags` AUTO_INCREMENT=55;');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->builder()->dropIfExists(self::TABLE_NAME);
    }
}
