<?php

namespace App\Http\Models\Backstage;

use App\Http\Repositories\Interfaces\CommonInterface;
use Illuminate\Database\Eloquent\Model;


class AdminNote extends Model implements CommonInterface
{

    protected $table = 'admin_note';

    protected $primaryKey = 'admin_note_id';

    const UPDATED_AT = 'admin_note_updated_time';

    const CREATED_AT = 'admin_note_created_time';

    //日志记录自定义信息
    public $adminLogTabel = 'admin_note_index';
    //日志记录新增删除 主键 对应下面label
    public $adminLogName = 'admin_note_name';
    //日志记录自定义信息
    public $adminLogMsg = '';
    //日志记录信息label
    public $adminLogData = [
        'admin_log_name'=>'节点',
        'admin_note_name'=>'名称',
        'admin_note_controller'=>'控制器name',
        'admin_note_action'=>'父级name',
        'admin_note_show'=>'是否显示',

    ];

    protected function getDateFormat()
    {
        return 'U';
    }

    /**
     * 得到节点数组数据
     * User: fly
     * @return mixed
     */
    public static function queryNoteArray(array $where)
    {

       return AdminNote::where($where)->orderBy('admin_note_sort', 'asc')->get()->toArray();
    }

    /**
     *查询指定节点id数据
     * User: fly
     * @param $adminRoleIds
     *
     * @return mixed
     */
    public static function queryNoteInId($adminRoleIds)
    {
       return  AdminNote::whereIn('admin_note_id',$adminRoleIds)->get();
    }

    /**
     * 查找一个指定条件
     * User: fly
     * @param $where
     *
     * @return mixed
     */
    public static function findWhereFirst($where)
    {
        return AdminNote::where($where)->first();
    }


    public static function queryData($param,$limit = 20)
    {
        $mode = AdminNote::with([]);
//                 ->where('admin_admin_show','=',0);
        if($param){
            if($param['admin_note_controller']){
                $adminRoleName = trim($param['admin_note_controller']);
                $mode->where('admin_note_controller','like',"%$adminRoleName%");
            }
        }

        $data   = $mode->paginate($limit);


        return $data;
    }

    public static function findData($id)
    {

    }

    public static function delData($id)
    {
        $status['ok'] = 1;

        $data = AdminNote::find($id);

        if(!$data){
            $status['ok'] = 2;
            $status['msg'] = '节点不存在';
            return $status;
        }

        if($data->delete() === false){
            $status['ok'] = 2;
            $status['msg'] = '删除失败';
            return $status;
        }


        return $status;
    }

    public static function saveData($id,$param)
    {
        $status['ok'] = 1;

        $data = AdminNote::findOrNew($id);

        $data->admin_note_name = $param['admin_note_name'];
        $data->admin_note_zh_name = $param['admin_note_zh_name'];
        $data->admin_note_default = $param['admin_note_default'];
        $data->admin_box = $param['admin_box'];
        $data->admin_note_controller = $param['admin_note_controller'];
        $data->admin_note_action = $param['admin_note_action'];
        $data->admin_admin_show = $param['admin_admin_show'];
        $data->admin_note_parent_id = $param['admin_note_parent_id'];
        $data->admin_note_sort = $param['admin_note_sort'];
        $data->admin_note_show = $param['admin_note_show'];
        $data->admin_has_subset = $param['admin_has_subset'];


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
}
