<?php

use CreateImageProductPivotTable as ImageProductTable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
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
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->dropIndex(self::COLUMN_NAME);
            $table->dropColumn(self::COLUMN_NAME);
        });
    }
}
