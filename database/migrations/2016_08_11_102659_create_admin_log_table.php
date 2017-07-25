<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_log', function (Blueprint $table) {
            $table->increments('admin_log_id');
            $table->bigInteger('admin_user_id')->comment("操作人ID");;
            $table->string('admin_user_name',30)->comment("操作人名称");
            $table->string('admin_log_controller',30)->comment("表名称");
            $table->text('admin_log_msg')->comment("表名称");
            $table->integer('created_time')->default(0)->comment("创建时间");
            $table->integer('updated_time')->default(0)->comment("更新时间");
            $table->index('admin_user_id');
            $table->index('admin_log_controller');
            $table->index('created_time');
            $table->index('updated_time');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('admin_log');
    }
}
