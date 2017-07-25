<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhoneCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phone_code', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->comment("验证码id");
            $table->string('phone',11)->comment("电话");
            $table->string('code',6)->unique()->comment("验证码");
            $table->smallInteger('type')->default(1)->comment("类型");
            $table->integer('valid_time')->default(0)->comment("过期时间");
            $table->integer('created_time')->default(0)->comment("创建时间");
            $table->integer('updated_time')->default(0)->comment("更新时间");
            $table->index('phone');
            $table->index('code');
            $table->index('valid_time');
            $table->index('created_time');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('phone_code');
    }
}
