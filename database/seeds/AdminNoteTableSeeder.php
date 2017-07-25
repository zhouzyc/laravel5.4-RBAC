<?php

use Illuminate\Database\Seeder;

class AdminNoteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_note')->insert([
            'admin_note_name' => '权限管理',
            'admin_note_zh_name' =>'权限管理',
            'admin_note_controller' => 'Admin',
            'admin_note_action' => 'index',
            'admin_admin_show' => 1,
            'admin_note_parent_id' => 0,
            'admin_note_sort' => 1,
            'admin_note_show' => 1,
            'admin_note_created_time' => time(),
            'admin_note_updated_time' => time(),
        ]);
        DB::table('admin_note')->insert([
            'admin_note_name' => '角色管理',
            'admin_note_zh_name' =>'角色管理',
            'admin_note_controller' => 'admin_role_index',
            'admin_note_action' => 'admin_role_save,admin_role_del',
            'admin_admin_show' => 1,
            'admin_note_parent_id' => 1,
            'admin_note_sort' => 2,
            'admin_note_show' => 1,
            'admin_note_created_time' => time(),
            'admin_note_updated_time' => time(),
        ]);
        DB::table('admin_note')->insert([
            'admin_note_name' => '编辑角色',
            'admin_note_zh_name' =>'编辑角色',
            'admin_note_controller' => 'admin_role_save',
            'admin_note_action' => 'admin_role_index',
            'admin_admin_show' => 1,
            'admin_note_parent_id' => 1,
            'admin_note_sort' => 3,
            'admin_note_show' => 0,
            'admin_note_created_time' => time(),
            'admin_note_updated_time' => time(),
        ]);
        DB::table('admin_note')->insert([
            'admin_note_name' => '删除角色',
            'admin_note_zh_name' =>'删除角色',
            'admin_note_controller' => 'admin_role_del',
            'admin_note_action' => 'admin_role_index',
            'admin_admin_show' => 1,
            'admin_note_parent_id' => 1,
            'admin_note_sort' => 4,
            'admin_note_show' => 0,
            'admin_note_created_time' => time(),
            'admin_note_updated_time' => time(),
        ]);
        DB::table('admin_note')->insert([
            'admin_note_name' => '账号管理',
            'admin_note_zh_name' =>'账号管理',
            'admin_note_controller' => 'admin_user_index',
            'admin_note_action' => 'admin_user_save,admin_user_del',
            'admin_admin_show' => 1,
            'admin_note_parent_id' => 1,
            'admin_note_sort' => 5,
            'admin_note_show' => 1,
            'admin_note_created_time' => time(),
            'admin_note_updated_time' => time(),
        ]);
        DB::table('admin_note')->insert([
            'admin_note_name' => '编辑账号',
            'admin_note_zh_name' =>'编辑账号',
            'admin_note_controller' => 'admin_user_save',
            'admin_note_action' => 'admin_user_index',
            'admin_admin_show' => 1,
            'admin_note_parent_id' => 1,
            'admin_note_sort' => 6,
            'admin_note_show' => 0,
            'admin_note_created_time' => time(),
            'admin_note_updated_time' => time(),
        ]);
        DB::table('admin_note')->insert([
            'admin_note_name' => '删除账号',
            'admin_note_zh_name' =>'删除账号',
            'admin_note_controller' => 'admin_user_del',
            'admin_note_action' => 'admin_user_index',
            'admin_admin_show' => 1,
            'admin_note_parent_id' => 1,
            'admin_note_sort' => 7,
            'admin_note_show' => 0,
            'admin_note_created_time' => time(),
            'admin_note_updated_time' => time(),
        ]);
        DB::table('admin_note')->insert([
            'admin_note_name' => '节点管理',
            'admin_note_zh_name' =>'节点管理',
            'admin_note_controller' => 'admin_note_index',
            'admin_note_action' => 'admin_note_save,admin_note_del',
            'admin_admin_show' => 1,
            'admin_note_parent_id' => 1,
            'admin_note_sort' => 8,
            'admin_note_show' => 1,
            'admin_note_created_time' => time(),
            'admin_note_updated_time' => time(),
        ]);
        DB::table('admin_note')->insert([
            'admin_note_name' => '编辑节点',
            'admin_note_zh_name' =>'编辑节点',
            'admin_note_controller' => 'admin_note_save',
            'admin_note_action' => 'admin_note_index',
            'admin_admin_show' => 1,
            'admin_note_parent_id' => 1,
            'admin_note_sort' => 9,
            'admin_note_show' => 0,
            'admin_note_created_time' => time(),
            'admin_note_updated_time' => time(),
        ]);
        DB::table('admin_note')->insert([
            'admin_note_name' => '删除节点',
            'admin_note_zh_name' =>'删除节点',
            'admin_note_controller' => 'admin_note_del',
            'admin_note_action' => 'admin_note_index',
            'admin_admin_show' => 1,
            'admin_note_parent_id' => 1,
            'admin_note_sort' => 10,
            'admin_note_show' => 0,
            'admin_note_created_time' => time(),
            'admin_note_updated_time' => time(),
        ]);
    }
}
