<?php

use ChingShop\Database\Migration;
use ChingShop\Modules\User\Model\Role;
use ChingShop\Modules\User\Model\User;
use Illuminate\Database\Schema\Blueprint;

/** @noinspection PhpIllegalPsrClassPathInspection */
class CreateRoleUserTable extends Migration
{
    /** @var string */
    private $tableName = Role::USER_ASSOCIATION_TABLE;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->builder()->create($this->tableName, function (Blueprint $table) {
            $table->increments('id');

            $table->integer(Role::FOREIGN_KEY)->unsigned();
            $table->integer(User::FOREIGN_KEY)->unsigned();

            $table->foreign(Role::FOREIGN_KEY)
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->foreign(User::FOREIGN_KEY)
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unique([User::FOREIGN_KEY, Role::FOREIGN_KEY]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->builder()->table($this->tableName, function (Blueprint $table) {
            $table->dropForeign($this->tableName.'_role_id_foreign');
            $table->dropForeign($this->tableName.'_user_id_foreign');
        });
        $this->builder()->drop($this->tableName);
    }
}
