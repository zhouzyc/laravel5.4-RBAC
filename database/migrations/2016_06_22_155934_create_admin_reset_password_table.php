<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminResetPasswordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_reset_password', function (Blueprint $table) {
            $table->increments('admin_reset_id')->comment("重置id");
            $table->string('admin_user_email',50)->comment("用户邮箱");
            $table->string('admin_reset_token',300)->comment("重置的token");
            $table->integer('admin_reset_exp')->default(0)->comment("重置有效期限");
            $table->integer('admin_reset_created_time')->default(0);
            $table->integer('admin_reset_updated_time')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('admin_reset_password');
    }
}
