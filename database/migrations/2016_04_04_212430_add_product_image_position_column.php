<?php

use CreateImageProductPivotTable as ImageProductTable;
use ChingShop\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

/** @noinspection PhpIllegalPsrClassPathInspection */
class AddProductImagePositionColumn extends Migration
{
    const TABLE_NAME = ImageProductTable::TABLE_NAME;
    const COLUMN_NAME = 'position';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->builder()->table(self::TABLE_NAME, function (Blueprint $table) {
            $table->tinyInteger(self::COLUMN_NAME)
                ->default(0)
                ->unsigned();
            $table->index(self::COLUMN_NAME, self::COLUMN_NAME);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->builder()->table(self::TABLE_NAME, function (Blueprint $table) {
            $table->dropIndex(self::COLUMN_NAME);
            $table->dropColumn(self::COLUMN_NAME);
        });
    }
}
