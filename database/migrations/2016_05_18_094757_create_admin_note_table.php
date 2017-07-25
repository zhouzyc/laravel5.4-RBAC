<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminNoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_note', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('admin_note_id')->comment("节点id");
            $table->string('admin_note_name',30)->comment("节点名称");
            $table->string('admin_note_zh_name',30)->comment("节点备注");
            $table->smallInteger('admin_note_default')->default(0)->comment("是否换行显示");
            $table->smallInteger('admin_box')->default(0)->comment("是否显示线");
            $table->string('admin_note_controller',50)->comment("控制的控制器");
            $table->string('admin_note_action',50)->comment("控制的action");
            $table->smallInteger('admin_admin_show')->default(0)->comment("是否只有超级管理员用");
            $table->smallInteger('admin_note_parent_id')->default(0)->comment("父级id 0是父级");
            $table->smallInteger('admin_note_sort')->default(1)->comment("排序");
            $table->smallInteger('admin_note_show')->default(1)->comment("是否显示该节点");
            $table->smallInteger('admin_has_subset')->default(1)->comment("是否有子集");
            $table->integer('admin_note_created_time')->default(0);
            $table->integer('admin_note_updated_time')->default(0);
            $table->index('admin_note_controller');
            $table->index('admin_note_action');
            $table->index('admin_note_created_time');
            $table->index('admin_note_updated_time');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('admin_note');
    }
}
