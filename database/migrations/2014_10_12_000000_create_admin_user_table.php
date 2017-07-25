<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_user', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('admin_user_id')->comment("用户id");
            $table->string('admin_user_name',30)->comment("用户名称");
            $table->string('admin_user_account',30)->unique()->comment("用户账号");
            $table->bigInteger('admin_user_phone')->unique()->comment("用户电话");
            $table->string('admin_user_mail', 60)->comment("用户邮箱");
            $table->smallInteger('admin_user_status')->default(1)->comment("用户状态");
            $table->string('admin_user_headimg',100)->comment("用户头像");
            $table->string('admin_user_password', 60)->comment("用户密码md5");
            $table->string('admin_user_remark',200)->default('')->comment("用户备注");
            $table->smallInteger('admin_user_admin')->default(0)->comment("是否是超级管理员1是");
            $table->string('remember_token',100)->default('')->comment("记住密码token");
            $table->integer('admin_user_created_time')->default(0)->comment("创建时间");
            $table->integer('admin_user_updated_time')->default(0)->comment("更新时间");
            $table->index('admin_user_created_time');
            $table->index('admin_user_updated_time');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('admin_user');
    }
}
