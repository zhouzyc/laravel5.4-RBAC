<?php

namespace App\Http\Repositories;



use App\Http\Repositories\Interfaces\AdminRoleInterface;
use App\Http\Models\Backstage\AdminRole;

class DbAdminRoleRepository implements AdminRoleInterface {


    /**
     * 查询角色多条数据
     * Web:2.0 版本
     * User: fly
     * @param $param
     * @param $limit
     *
     * @return mixed
     */
    public static function queryData($param,$limit)
    {
        $mode = AdminRole::leftJoin('admin_role_user','admin_role.admin_role_id','=','admin_role_user.admin_role_id')
            ->leftJoin('admin_note','admin_role_user.admin_note_id','=','admin_note.admin_note_id')
            ->groupBy('admin_role.admin_role_id')
            ->select(\DB::raw(config('database.connections.mysql.prefix').'admin_role.*,
                            GROUP_CONCAT('.config('database.connections.mysql.prefix').'admin_note.admin_note_name) as admin_note_names'));
        if($param){
            if($param['admin_role_name']){
                $adminRoleName = trim($param['admin_role_name']);
                $mode->where('admin_role.admin_role_name','like',"%$adminRoleName%");
            }
        }

        $data   = $mode->paginate($limit);

        return $data;
    }

    /**
     * 得到下拉框需要数据
     * Web:2.0 版本
     * User: fly
     * @return mixed
     */
    public static function querySelectList()
    {
        return AdminRole::where([])->get();
    }

    public static function findData($param){

    }

    public static function delData($id){

    }

    public static function saveData($id,$data){

    }


}