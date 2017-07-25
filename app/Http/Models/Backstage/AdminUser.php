<?php

namespace App\Http\Models\Backstage;

use App\Http\Controllers\Backstage\Controller;
use App\Http\Repositories\Interfaces\CommonInterface;
use Illuminate\Foundation\Auth\User as Authenticatable;



class AdminUser extends Authenticatable implements CommonInterface
{

    protected $table = 'admin_user';

    protected $primaryKey = 'admin_user_id';

    const UPDATED_AT = 'admin_user_updated_time';

    const CREATED_AT = 'admin_user_created_time';

    //日志记录自定义信息
    public $adminLogTabel = 'admin_user_index';
    //日志记录新增删除 主键 对应下面label
    public $adminLogName = 'admin_user_account';
    //日志记录自定义信息
    public $adminLogMsg = '';
    //日志记录信息label
    public $adminLogData = [
        'admin_log_name'=>'账号',
        'admin_user_name'=>'真实姓名',
        'admin_user_remark'=>'备注',
        'admin_user_phone'=>'手机号',
        'admin_user_account'=>'',
        'admin_user_headimg'=>'头像',
        'admin_user_status'=>array(
            'name'=>'状态',
            'val'=>array(1=>'启用',2=>'停用')
        ),
    ];

    public static $AdminUserstatus = array(1=>'启用',2=>'停用');

    public function getAuthIdentifier(){
        return $this->admin_user_id;
    }
    public function getAuthPassword()
    {
        return $this->admin_user_password;
    }
    protected function getDateFormat()
    {
        return 'U';
    }
    public function getAdminUserPhoneAttribute($value)
    {
        $model = new Controller();
        $method = $model->getCurrentAction();

        $controllerData =  explode('\\',$method['controller']);
        $controller = ($controllerData[3]);
        $controller = str_replace('Controller','',$controller);

        if($controller == 'AdminUser' && $method['method'] == 'save'){
            return $value;
        }
        return str_replace(substr($value,3,4),'****',$value);

    }
    public function access()
    {
        return $this->hasOne('App\Http\Models\Backstage\AdminAccess', 'admin_user_id');
    }

    /**
     * 查找关联access用户
     * User: fly
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function findAccessOrNew($id)
    {
        return AdminUser::with('access')->findOrNew($id);
    }
    /**
     * 查找关联access用户
     * User: fly
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function findAccessRoleOrNew($id)
    {
        return AdminUser::with(['access','access.role'])->findOrNew($id);
    }

    /**
     * 查找指定条件用户所属节点数组
     * User: fly
     * @param $where
     *
     * @return array
     */
    public static function queryUserNoteWhereArray($where)
    {
       return AdminUser::with(
            array(
                'access.role.roleUser',
                'access.role',
                'access',
                'access.role.roleUser.note'
            )
        )->where($where)->first()->toArray();
    }
    /**
     * 用邮箱查找用户
     * Web:2.0 版本
     * User: fly
     * @param $email
     *
     * @return mixed
     */
    public static function findEmailData($email){
        return  AdminUser::where('admin_user_mail','=',$email)->first();
    }


    /**
     * 查找指定id用户
     * User: fly
     * @param $id
     *
     * @return mixed
     */
    public static function findOne($id)
    {
        return AdminUser::find($id);
    }

    /**
     * 查找指定条件的用户
     * User: fly
     * @param $where
     *
     * @return mixed
     */
    public static function findWhere($where)
    {
        return AdminUser::where($where)->first();
    }
    /**
     * 得到状态名称数组
     * User: fly
     * @param bool|false $isAll  是否显示全部
     *
     * @return array
     */
    public static function getStatusName($isAll = false)
    {
        $statusName = self::$AdminUserstatus;
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
        $statusName = self::$AdminUserstatus;
        if(isset($statusName[$status])){
            return $statusName[$status];
        }
        return '';
    }

    /**
     * 得到用户信息
     * Web:2.0 版本
     * User: fly
     * @param $param
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static  function queryData($param,$limit = 10)
    {

        $mode = AdminUser::leftJoin('admin_access','admin_access.admin_user_id','=','admin_user.admin_user_id')
                         ->leftJoin('admin_role','admin_role.admin_role_id','=','admin_access.admin_role_id')
                         ->select('admin_user.*','admin_role.admin_role_name','admin_role.admin_role_id')
                         ->where('admin_user.admin_user_admin','=',0);
        if($param){
            if($param['admin_user_name']){
                $adminUserName = trim($param['admin_user_name']);
                $mode->where('admin_user.admin_user_name','like',"%$adminUserName%");
            }
            if($param['admin_user_account']){
                $adminUserAccount = trim($param['admin_user_account']);
                $mode->where('admin_user.admin_user_account','like',"%$adminUserAccount%");
            }
            if($param['admin_user_phone']){
                $adminUserPhone = trim($param['admin_user_phone']);
                $mode->where('admin_user.admin_user_phone','=',$adminUserPhone);
            }
            if($param['admin_user_role']){
                $adminUserRole = $param['admin_user_role'];
                $mode->where('admin_role.admin_role_id','=',$adminUserRole);
            }
            if($param['admin_user_status']){
                $adminUserStatus = $param['admin_user_status'];
                $mode->where('admin_user.admin_user_status','=',$adminUserStatus);
            }
        }

        $data   = $mode->paginate(20);

        return $data;
    }



    public static function findData($id)
    {

    }

    /**
     * 新增修改数据
     * Web:2.0 版本
     * User: fly
     * @param $id
     * @param $param
     *
     * @return mixed
     */
    public static function saveIndexData($id,$param)
    {

        $status['ok'] = 1;

        $data = AdminUser::findAccessOrNew($id);

        $data->admin_user_name = $param['admin_user_name'];
        $data->admin_user_headimg = $param['admin_user_headimg']?$param['admin_user_headimg']:'';
        if(isset($param['admin_user_account'])){
            $data->admin_user_account = $param['admin_user_account'];
        }
        if(isset($param['admin_user_password']) && $param['admin_user_password']){
            $data->admin_user_password =  bcrypt(($param['admin_user_password']));
        }
        if(isset($param['admin_user_mail']) && $param['admin_user_mail']){
            $data->admin_user_mail =  $param['admin_user_mail'];
        }
        if(isset($param['admin_user_phone']) && $param['admin_user_phone']){
            $data->admin_user_phone =  $param['admin_user_phone'];
        }
        if(isset($param['admin_user_status']) && $param['admin_user_phone']){
            $data->admin_user_status =  (int)$param['admin_user_status'];
        }
        if(isset($param['admin_user_remark']) && $param['admin_user_remark']  ){
            $data->admin_user_remark =  $param['admin_user_remark'];
        }else{
            $data->admin_user_remark =  '';
        }
        $data->remember_token =  '';
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
     * 新增修改数据
     * Web:2.0 版本
     * User: fly
     * @param $id
     * @param $param
     *
     * @return mixed
     */
    public static function saveData($id,$param)
    {

        $status['ok'] = 1;

        $data = AdminUser::findAccessOrNew($id);

        $data->admin_user_name = $param['admin_user_name'];
        $data->admin_user_headimg = $param['admin_user_headimg'];
        if(isset($param['admin_user_account'])){
            $data->admin_user_account = $param['admin_user_account'];
        }
        if(isset($param['admin_user_password']) && $param['admin_user_password']){
            $data->admin_user_password =  bcrypt(($param['admin_user_password']));
        }
        if(isset($param['admin_user_mail']) && $param['admin_user_mail']){
            $data->admin_user_mail =  $param['admin_user_mail'];
        }
        if(isset($param['admin_user_phone']) && $param['admin_user_phone']){
            $data->admin_user_phone =  $param['admin_user_phone'];
        }
        if(isset($param['admin_user_status']) && $param['admin_user_phone']){
            $data->admin_user_status =  (int)$param['admin_user_status'];
        }
        if(isset($param['admin_user_remark']) && $param['admin_user_remark']  ){
            $data->admin_user_remark =  $param['admin_user_remark'];
        }
        $original = clone $data;

        if($data->save() === false){
            $status['ok'] = 2;
            $status['msg'] = '操作失败';
            return $status;
        }
        $save = true;

        if(!isset($param['admin_role_id']) && $original->admin_user_admin != 1){
            $status['ok'] = 2;
            $status['msg'] = '请先选择角色';
            return $status;
        }

        if(isset($original->access->admin_role_id) && isset($param['admin_role_id']) && $original->access->admin_role_id == $param['admin_role_id']){
            $save = false;
        }

        if(!empty($param['admin_role_id']) && $save){

            $adminRoleData = AdminRole::findOne($param['admin_role_id']);

            if(!$adminRoleData){
                $status['ok'] = 2;
                $status['msg'] = '角色不存在';
                return $status;
            }

            if(!AdminAccess::delWhereAll(array('admin_user_id'=>$data->admin_user_id))){
                $status['ok'] = 2;
                $status['msg'] = '操作失败';
                return $status;
            }
            if($id){
                AdminRole::findWhere(['admin_role_id'=>$original->access->admin_role_id])->decrement('admin_role_number');
            }
            AdminRole::findWhere(['admin_role_id'=>$param['admin_role_id']])->increment('admin_role_number');

            $original->adminLogMsg='将账号['.$data->admin_user_account.']所属角色修改为('.$adminRoleData->admin_role_name.')<br>';

            if(!AdminAccess::addData($adminRoleData->admin_role_id,$data->admin_user_id)){
                $status['ok'] = 2;
                $status['msg'] = '操作失败';
                return $status;
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

        $data = AdminUser::findAccessOrNew($id);

        if(!$data){
            $status['ok'] = 2;
            $status['msg'] = '用户不存在';
            return $status;
        }

        if($data->delete() === false){
            $status['ok'] = 2;
            $status['msg'] = '删除失败';
            return $status;
        }

        if(!AdminAccess::delWhereAll(array('admin_user_id'=>$id))){
            $status['ok'] = 2;
            $status['msg'] = '删除失败';
            return $status;
        }

        AdminRole::findWhere(['admin_role_id'=>$data->access->admin_role_id])->decrement('admin_role_number');

        return $status;
    }


    /**
     * 修改制定用户密码
     * Web:2.0 版本
     * User: fly
     * @param $admin_user_id
     * @param $password
     *
     * @return bool
     */
    public static function savePasswordData($admin_user_id,$password){
        $user = AdminUser::findOne($admin_user_id);

        $user->admin_user_password = bcrypt($password);
        if( $user->save() === false){
            return false;
        }

        return $user;
    }

    /**
     * 查找用户管理的节点
     * User: fly
     * @param $admin_user_id
     *
     * @return array
     */
    public static function queryUserNoetArray($admin_user_id)
    {
        $data =   AdminUser::with(
            array(
                'access.role.roleUser',
                'access.role',
                'access',
                'access.role.roleUser.note'=>function($query){
                    $query->orderBy('admin_note_sort', 'asc');
                }
            )
        )->where('admin_user_id','=',$admin_user_id)->first()->toArray();

        return $data;
    }

    /**
     * 修改账号状态
     * User: fly
     * @param $id
     * @param $status
     */
    public static function statusData($id,$statusId)
    {
        $status['ok'] = 1;

        $data = AdminUser::findOne($id);

        if(!$data){
            $status['ok'] = 2;
            $status['msg'] = '用户不存在';
            return $status;
        }
        $data->admin_user_status = $statusId;

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
     * 修改角色
     * User: fly
     * @param $id
     * @param $adminRoleId
     */
    public static function upRoleData($id,$adminRoleId)
    {
        $status['ok'] = 1;

        $data = AdminUser::findAccessOrNew($id);

        $original = clone $data;

        $adminRoleData = AdminRole::findOne($adminRoleId);

        if(!$adminRoleData){
            $status['ok'] = 2;
            $status['msg'] = '角色不存在';
            return $status;
        }

        if(!AdminAccess::delWhereAll(array('admin_user_id'=>$data->admin_user_id))){
            $status['ok'] = 2;
            $status['msg'] = '操作失败';
            return $status;
        }

        $original->adminLogMsg='将账号['.$data->admin_user_account.']所属角色修改为('.$adminRoleData->admin_role_name.')<br>';

        if(!AdminAccess::addData($adminRoleData->admin_role_id,$data->admin_user_id)){
            $status['ok'] = 2;
            $status['msg'] = '操作失败';
            return $status;
        }


        AdminRole::findWhere(['admin_role_id'=>$original->access->admin_role_id])->decrement('admin_role_number');

        AdminRole::findWhere(['admin_role_id'=>$adminRoleId])->increment('admin_role_number');

        //返回原始数据对象方记录log
        $status['original'] = $original;

        return $status;
    }
}
