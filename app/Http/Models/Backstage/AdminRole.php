<?php

namespace App\Http\Models\Backstage;

use App\Http\Repositories\Interfaces\CommonInterface;
use Illuminate\Database\Eloquent\Model;


class AdminRole extends Model implements CommonInterface
{


    protected $table = 'admin_role';

    protected $primaryKey = 'admin_role_id';


    const UPDATED_AT = 'admin_role_updated_time';

    const CREATED_AT = 'admin_role_created_time';

    //日志记录自定义信息
    public $adminLogTabel = 'admin_role_index';
    //日志记录新增删除 主键 对应下面label
    public $adminLogName = 'admin_role_name';
    //日志记录自定义信息
    public $adminLogMsg = '';
    //日志记录信息label
    public $adminLogData = [
        'admin_log_name'=>'角色',
        'admin_role_name'=>'名称',
        'admin_role_status'=>array(
            'name'=>'状态',
            'val'=>array(1=>'启用',2=>'停用')
        ),
    ];

    public static $AdminRolestatus = array(1=>'启用',2=>'停用');

    protected function getDateFormat()
    {
        return 'U';
    }


    public function roleUser()
    {

        return $this->hasMany('App\Http\Models\Backstage\AdminRoleUser', 'admin_role_id');
    }


    /**
     * 查询一关联数据
     * Web:2.0 版本
     * User: fly
     * @param $param
     */
    public static function findRoleUserOrNew($id){
        return AdminRole::with('roleUser')->findOrNew($id);
    }


    public static function queryWhereArray(array $where)
    {
        return AdminRole::where($where)->get()->toArray();
    }

    public static function findWhere($where)
    {
        return AdminRole::where($where)->first();
    }
    public static function findOne($id)
    {
        return AdminRole::find($id);
    }

    /**
     * 得到角色状态名称数组
     * User: fly
     * @param bool|false $isAll  是否显示全部
     *
     * @return array
     */
    public static function getStatusName($isAll = false)
    {
        $statusName = self::$AdminRolestatus;
        if($isAll){
            array_unshift($statusName, "全部");
        }

        return $statusName;
    }

    /**
     * 列表得到相对的状态
     * User: fly
     *
     * @param $status
     *
     * @return string
     */
    public static function setStatusName($status)
    {
        $statusName = self::$AdminRolestatus;
        if(isset($statusName[$status])){
            return $statusName[$status];
        }
        return '';
    }
    /**
     * 查询角色列表
     * Web:2.0 版本
     * User: fly
     * @param $param
     *
     * @return array
     */
    public static function queryData($param,$limit = 10)
    {
        $quer = ' GROUP_CONCAT('.config('database.connections.mysql.prefix').'admin_note.admin_note_zh_name) as admin_note_names ';

        $mode = AdminRole::
                           leftJoin('admin_role_user','admin_role.admin_role_id','=','admin_role_user.admin_role_id')
                         ->leftJoin('admin_note','admin_role_user.admin_note_id','=','admin_note.admin_note_id')
                         ->selectRaw($quer)
                         ->addSelect('admin_role.*' )
                         ->groupBy([
                             'admin_role.admin_role_id',
                             'admin_role.admin_role_name',
                             'admin_role.admin_role_status',
                             'admin_role.admin_role_number',
                             'admin_role.admin_role_created_time',
                             'admin_role.admin_role_updated_time',
                         ]);


        if($param){
            if($param['admin_role_name']){
                $adminRoleName = trim($param['admin_role_name']);
                $mode->where('admin_role.admin_role_name','like',"%$adminRoleName%");
            }
            if($param['admin_role_status'] && $param['admin_role_status']!=0){
                $adminRoleStatus = $param['admin_role_status'];
                $mode->where('admin_role.admin_role_status','=',$adminRoleStatus);
            }
        }

        $roleData   = $mode->paginate($limit);

        return $roleData;

    }



    /**
     * 查询一条数据
     * Web:2.0 版本
     * User: fly
     * @param $param
     */
    public static function findData($id){

    }


    /**
     * 新增修改数据
     * Web:2.0 版本
     * User: fly
     * @param $id
     * @param $param
     */
    public static  function saveData($id,$param)
    {
        $status['ok'] = 1;

        $data = AdminRole::findRoleUserOrNew($id);

        $data->admin_role_name = $param['admin_role_name'];

        $data->admin_role_status = $param['admin_role_status'];

        $original = clone $data;

        if($data->save() === false){
            $status['ok'] = 2;
            $status['msg'] = '操作失败';
            return $status;
        }

        $hasNoteData = array();
        if($data->roleUser->toArray()){
            foreach($data->roleUser->toArray() as $hasNoet){
                $hasNoteData[] = $hasNoet['admin_note_id'];
            }
        }
        if(empty($param['role_user'])){
            $param['role_user'] = array();
        }
        $diffData = array_merge($hasNoteData,$param['role_user']);

        if(!empty($diffData)){

            if(!AdminRoleUser::delWhereAll(array('admin_role_id'=>$data->admin_role_id))){
                $status['ok'] = 2;
                $status['msg'] = '操作失败';
                return $status;
            }
            if(empty($param['role_user'])){
                $original->adminLogMsg.='将角色['.$data->admin_role_name.']所属节点清空';
            }else{
                $insertData = array();
                $adminRoleIds = array();
                foreach($param['role_user'] as $v){
                    $adminRoleIds[] = $v;
                }
                $adminNoteData = AdminNote::queryNoteInId($adminRoleIds);
                if(!empty($adminNoteData)){
                    $original->adminLogMsg.='将角色['.$data->admin_role_name.']所属节点修改为(';
                    foreach($adminNoteData as $v){
                        $insertData[] = array(
                            'admin_role_id'=>$data->admin_role_id,
                            'admin_note_id'=>$v->admin_note_id,
                        );
                        $original->adminLogMsg.= $v->admin_note_name.' | ';
                    }
                    $original->adminLogMsg.=')<br>';
                }

                if(!empty($insertData)){
                    if(!AdminRoleUser::addDataAll($insertData)){
                        $status['ok'] = 2;
                        $status['msg'] = '操作失败';
                        return $status;
                    }
                }
            }

        }
        //返回原始数据对象方记录log
        $status['original'] = $original;
        return $status;
    }


    /**
     * 删除数据
     * Web:2.0 版本
     * User: fly
     * @param $id
     *
     * @return mixed
     */
    public static function delData($id)
    {
        $status['ok'] = 1;

        if(AdminRole::destroy($id) === false){
            $status['ok'] = 2;
            $status['msg'] = '删除失败';
            return $status;
        }

        if(!AdminRoleUser::delWhereAll(array('admin_role_id'=>$id))){
            $status['ok'] = 2;
            $status['msg'] = '删除失败';
            return $status;
        }
        return $status;
    }

    /**
     * 修改角色状态
     * User: fly
     * @param $id
     * @param $status
     */
    public static function statusData($id,$statusId)
    {
        $status['ok'] = 1;

        $data = AdminRole::findOne($id);

        if(!$data){
            $status['ok'] = 2;
            $status['msg'] = '用户不存在';
            return $status;
        }
        $data->admin_role_status = $statusId;

        $original = clone $data;

        if($data->save() === false){
            $status['ok'] = 2;
            $status['msg'] = '操作失败';
            return $status;
        }

        //返回原始数据对象方记录log
        $status['original'] = $original;

        return $status;
    }

    /**
     * 查询角色列表
     * Web:2.0 版本
     * User: fly
     * @return array
     */
    public static function queryRoleSelectList($isAll = false)
    {
        $roleData = AdminRole::queryWhereArray(['admin_role_status'=>1]);

        $data = array();
        if($roleData){
            foreach($roleData as $role){
                $data[$role['admin_role_id']] = $role['admin_role_name'];
            }
        }
        if($isAll){
            $returnData = array_prepend($data,"全部",0);
        }else{
            $returnData = $data;
        }

        return $returnData;
    }

}
