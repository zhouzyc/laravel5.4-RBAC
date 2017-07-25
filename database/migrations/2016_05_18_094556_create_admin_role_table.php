<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_role', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('admin_role_id')->comment("角色id");
            $table->string('admin_role_name',30)->comment("用户名称");
            $table->smallInteger('admin_role_status')->comment("角色状态");
            $table->smallInteger('admin_role_number')->default(0)->comment("角色管理数量");
            $table->integer('admin_role_created_time')->default(0);
            $table->integer('admin_role_updated_time')->default(0);
            $table->index('admin_role_name');
            $table->index('admin_role_status');
            $table->index('admin_role_created_time');
            $table->index('admin_role_updated_time');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('admin_role');
    }
}
