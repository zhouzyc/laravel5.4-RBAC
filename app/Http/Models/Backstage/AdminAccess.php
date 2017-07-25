<?php

namespace App\Http\Models\Backstage;

use App\Http\Repositories\Interfaces\CommonInterface;
use Illuminate\Database\Eloquent\Model;


class AdminAccess extends Model implements CommonInterface
{

    protected $table = 'admin_access';

    const UPDATED_AT = 'admin_updated_time';

    const CREATED_AT = 'admin_created_time';

    protected function getDateFormat()
    {
        return 'U';
    }

    public function role()
    {

        return $this->hasOne('App\Http\Models\Backstage\AdminRole', 'admin_role_id','admin_role_id');
    }

    public static function delWhereAll(array $where)
    {
        if(AdminAccess::where($where)->delete() === false){
            return false;
        }
        return true;
    }

    public static function addData($admin_role_id,$admin_user_id)
    {
        $adminAccess = new AdminAccess();
        $adminAccess->admin_role_id = $admin_role_id;
        $adminAccess->admin_user_id = $admin_user_id;
        if($adminAccess->save() === false){
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
