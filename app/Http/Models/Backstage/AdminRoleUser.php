<?php

namespace App\Http\Models\Backstage;

use App\Http\Repositories\Interfaces\CommonInterface;
use Illuminate\Database\Eloquent\Model;


class AdminRoleUser extends Model implements CommonInterface
{

    protected $table = 'admin_role_user';

    const UPDATED_AT = 'admin_updated_time';

    const CREATED_AT = 'admin_created_time';

    protected function getDateFormat()
    {
        return 'U';
    }

    public function note()
    {

        return $this->hasOne('App\Http\Models\Backstage\AdminNote', 'admin_note_id','admin_note_id');
    }

    /**
     * 删除指定条件数据
     * User: fly
     * @param $where
     *
     * @return bool
     */
    public static function delWhereAll(array $where)
    {
        if(AdminRoleUser::where($where)->delete() === false){
            return false;
        }
        return true;
    }

    public static function addDataAll(array $insertData)
    {
        if(AdminRoleUser::insert($insertData) === false){
            return false;
        }
        return true;
    }

    public static function queryData($param,$limit)
    {

    }

    public static function findData($id)
    {

    }

    public static function delData($id)
    {

    }

    public static function saveData($id,$param)
    {

    }


}
