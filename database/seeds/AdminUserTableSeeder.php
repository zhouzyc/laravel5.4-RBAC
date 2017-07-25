<?php

use Illuminate\Database\Seeder;

class AdminUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_user')->insert([
            'admin_user_name' => '超级管理员',
            'admin_user_account' =>'admin',
            'admin_user_password' => bcrypt(123456),
            'admin_user_admin' => 1,
            'admin_user_phone' => 0,
            'admin_user_mail' => '',
            'admin_user_remark' => '',
            'remember_token' => '',
            'admin_user_headimg' => 'http://img3.imgtn.bdimg.com/it/u=1088200534,4215449739&fm=21&gp=0.jpg',
            'admin_user_created_time' => time(),
            'admin_user_updated_time' => time(),
        ]);
    }
}
