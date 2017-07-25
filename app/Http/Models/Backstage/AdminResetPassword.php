<?php

namespace App\Http\Models\Backstage;

use App\Http\Repositories\Interfaces\CommonInterface;
use Illuminate\Database\Eloquent\Model;


class AdminResetPassword extends Model implements CommonInterface
{

    protected $table = 'admin_reset_password';

    protected $primaryKey = 'admin_reset_id';

    const UPDATED_AT = 'admin_reset_updated_time';

    const CREATED_AT = 'admin_reset_created_time';

    protected function getDateFormat()
    {
        return 'U';
    }

    /**
     * 新增密码重置数据
     * Web:2.0 版本
     * User: fly
     * @param $email
     * @param $token
     *
     * @return AdminResetPassword|bool
     */
    public static function addData($email,$token)
    {
        $adminResetModel = new AdminResetPassword();
        $adminResetModel->admin_user_email = $email;
        $adminResetModel->admin_reset_token = $token;
        $adminResetModel->admin_reset_exp = (time()+600);
        if($adminResetModel->save() === false){
            return false;
        }
        return $adminResetModel;
    }

    /**
     * 用token查找记录
     * Web:2.0 版本
     * User: fly
     * @param $resetToken
     *
     * @return mixed
     */
    public static function findTokenData($resetToken)
    {
        return AdminResetPassword::where('admin_reset_token',$resetToken)
                        ->orderBy('admin_reset_created_time','desc')
                        ->first();
    }

    /**
     * 删除指定id记录
     * Web:2.0 版本
     * User: fly
     * @param $admin_reset_id
     *
     * @return mixed
     */
    public static function delData($admin_reset_id)
    {

        $status['ok'] = 1;

        if(AdminResetPassword::destroy($admin_reset_id) === false){
            $status['ok'] = 2;
            $status['msg'] = '删除失败';
            return $status;
        }

        return $status;
    }

    public static function queryData($param,$limit)
    {

    }

    public static function findData($id)
    {

    }

    public static function saveData($id,$param)
    {

    }
}
