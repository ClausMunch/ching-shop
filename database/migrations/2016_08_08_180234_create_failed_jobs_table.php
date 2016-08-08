<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFailedJobsTable extends Migration
{
    const TABLE_NAME = 'failed_jobs';

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
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->timestamp('failed_at')->useCurrent();
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
        Schema::drop(self::TABLE_NAME);
    }
}
