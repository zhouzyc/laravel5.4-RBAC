<?php

namespace App\Http\Controllers\Backstage;


use App\Events\AdminLogEvent;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Models\Backstage\AdminAccess;
use App\Http\Models\Backstage\AdminRole;
use App\Http\Models\Backstage\AdminUser;
use Illuminate\Support\Facades\URL;

class IndexController extends Controller
{


    public function index()
    {

        //输出界面
        return $this->showView('Backstage.Index.index');
    }

    public function save(Request $request)
    {

        //获取表单参数
        $id = \Auth::user()['admin_user_id'] ;

        //验证表单提交参数
        $fromErr = $this->validatorFrom([
            'param.admin_user_mail'=>'email|max:60|unique:admin_user,admin_user_mail,'.$id.',admin_user_id',
            'param.admin_user_name'=>'alpha_dash|max:20',
            'param.admin_user_password'=>'alpha_dash|max:60',
        ],array('param.admin_user_mail.unique'=>'邮箱已被暂用'));
        if($fromErr){
            return $fromErr;
        }

        if($_POST){
            //获取表单参数
            //验证表单提交参数
            $fromErr = $this->validatorFrom([
                'param.admin_user_name'=>'required',
                'param.admin_user_mail'=>'required',
            ]);
            if($fromErr){
                return $fromErr;
            }
            $param = Input::get('param') ;

            \DB::beginTransaction();

            unset($param['admin_user_account']);
//            unset($param['admin_user_password']);
            unset($param['admin_user_phone']);
            unset($param['admin_user_status']);
            unset($param['admin_user_remark']);

            $status = AdminUser::saveIndexData($id,$param);//新增修改数据

            if($status['ok'] == 2){
                \DB::rollback();
                return echoErr($status['msg']);
            }

            \DB::commit();

            //记录日志
            \Event::fire(new AdminLogEvent($status['original']));

            return echoOk();
//            return redirect('message')->with(array('title'=>'操作成功','type'=>2,'url'=>'','wait'=>2,'msg'=>'操作成功'));
        }
        $str = '修改个人资料';
        //设置面包屑
        $this->breadcrumb = array(
            array('name'=>'首页','url'=>URL::route('admin_index')),
            array('name'=>$str,'url'=>'')
        );
        //设置标题
        $this->topTitle = $str;

        $data = AdminUser::findAccessRoleOrNew($id);

        $roleData = AdminRole::queryRoleSelectList();
        //输出界面
        return $this->showView('Backstage.Index.save',array('data'=>$data,'roleData'=>$roleData));

    }
}
