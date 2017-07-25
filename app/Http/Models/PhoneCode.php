<?php

namespace App\Http\Models;

use App\Http\Repositories\Interfaces\CommonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class PhoneCode extends Model implements CommonInterface
{

    protected $table = 'phone_code';

    protected $primaryKey = 'id';

    const UPDATED_AT = 'updated_time';

    const CREATED_AT = 'created_time';

    protected function getDateFormat()
    {
        return 'U';
    }


    public static function queryData($param,$limit)
    {

    }


    public static function isSend($where)
    {
       return PhoneCode::where($where)->first();
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

    public static function addData($phone,$code,$type)
    {

        $status['ok'] = 1;
        $model = new PhoneCode();
        $model->phone = $phone;
        $model->code = $code;
        $model->type = $type;
        $model->valid_time = (time()+300);
        if($model->save() === false){
            $status['ok'] = 2;
            $status['msg'] = '修改失败';
            return $status;
        }
        return $status;
    }
}
