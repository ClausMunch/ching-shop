<?php

use ChingShop\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

/** @noinspection PhpIllegalPsrClassPathInspection */
class CreateRolesTable extends Migration
{
    /** @var string */
    private $tableName = 'roles';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->builder()->create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();

            $table->index('name');
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
        $this->builder()->dropIfExists($this->tableName);
    }
}
