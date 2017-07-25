<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminRoleUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_role_user', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('admin_role_id')->default(0)->comment("角色id");
            $table->integer('admin_note_id')->default(0)->comment("节点id");
            $table->integer('admin_updated_time')->default(0);
            $table->integer('admin_created_time')->default(0);
            $table->index('admin_role_id');
            $table->index('admin_note_id');
            $table->index('admin_updated_time');
            $table->index('admin_created_time');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('admin_role_user');
    }
}
