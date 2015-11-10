<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use ChingShop\User\RoleResource;
use ChingShop\User\UserResource;

class CreateRoleUserTable extends Migration
{
    /** @var string */
    private $tableName = RoleResource::USER_ASSOCIATION_TABLE;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');

            $table->integer(RoleResource::FOREIGN_KEY)->unsigned();
            $table->integer(UserResource::FOREIGN_KEY)->unsigned();

            $table->foreign(RoleResource::FOREIGN_KEY)
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->foreign(UserResource::FOREIGN_KEY)
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unique([UserResource::FOREIGN_KEY, RoleResource::FOREIGN_KEY]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->dropForeign($this->tableName . '_role_id_foreign');
            $table->dropForeign($this->tableName . '_user_id_foreign');
        });
        Schema::drop($this->tableName);
    }
}
