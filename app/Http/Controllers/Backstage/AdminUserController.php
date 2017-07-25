<?php

namespace App\Http\Controllers\Backstage;



use App\Events\AdminLogEvent;
use App\Http\Models\Backstage\AdminAccess;
use App\Http\Models\Backstage\AdminRole;
use App\Http\Models\Backstage\AdminUser;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;


class AdminUserController extends Controller
{

    /**
     * 账号列表
     * Web:2.0 版本
     * User: fly
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {


        //获取表单参数
        $param = Input::get('param') ;
        //注册参数到界面
        $this->param = $param ;
        //设置面包屑
        $this->breadcrumb = array(
            array('name'=>'账号管理','url'=>URL::route('admin_user_index'))
        );
        //设置标题
        $this->topTitle = '账号列表';
        //设置搜索条件和参数
        $data = AdminUser::queryData($param);

        //输出界面
        return $this->showView('Backstage.AdminUser.index',['data'=>$data]);
    }

    /**
     * 新增修改数据
     * Web:2.0 版本
     * User: fly
     */
    public function save(Request $request)
    {
        //获取表单参数
        $id = Input::get('id') ;

        if($_POST){

            //验证表单提交参数
            $fromErr = $this->validatorFrom([
                'param.admin_user_mail'=>'required|email|max:60|unique:admin_user,admin_user_mail,'.$id.',admin_user_id',
                'param.admin_user_name'=>'required|alpha_dash|max:10',
                'param.admin_user_phone'=>'required|unique:admin_user,admin_user_phone,'.$id.',admin_user_id',
                'param.admin_user_status'=>'required|Integer',
                'param.admin_user_remark'=>'max:200',
                'param.admin_user_account'=>'required|alpha_dash|max:30|unique:admin_user,admin_user_account,'.$id.',admin_user_id',
                'param.admin_user_password'=>'alpha_dash|max:50',
            ],array('param.admin_user_account.unique'=>'账号已被暂用',
                'param.admin_user_phone.unique'=>'手机已被暂用','param.admin_user_mail.unique'=>'邮箱已被暂用'));

            if($fromErr){
                return $fromErr;
            }
            $param = Input::get('param');

            \DB::beginTransaction();
            $status = AdminUser::saveData($id,$param);//新增修改数据

            if($status['ok'] == 2){
                \DB::rollback();
                return echoErr($status['msg']);
            }
            \DB::commit();

            //记录日志
            \Event::fire(new AdminLogEvent($status['original']));

            return echoOk();
//          return redirect('message')->with(array('title'=>'操作失败','type'=>2,'url'=>'','wait'=>5,'msg'=>$fromErr));
        }
        $str = '添加账号';
        if($id){
            $str = '修改账号';
        }
        //设置面包屑
        $this->breadcrumb = array(
            array('name'=>'账号管理','url'=>URL::route('admin_user_index')),
            array('name'=>$str,'url'=>'')
        );
        //设置标题
        $this->topTitle = $str;

        $data = AdminUser::findAccessOrNew($id);

        $roleData = AdminRole::queryRoleSelectList();
        //输出界面
        return $this->showView('Backstage.AdminUser.save',array('data'=>$data,'roleData'=>$roleData));
    }
    /**
     * 删除账号
     * Web:2.0 版本
     * User: fly
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function del(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|Integer',
        ]);

        $id  = Input::get('id');

        $adminUserData = AdminUser::findOne($id);

        if(!$adminUserData || $adminUserData['admin_user_admin'] == 1){
            return echoErr('账号不存在');
        }
        \DB::beginTransaction();

        $status = AdminUser::delData($id);

        if($status['ok'] == 2){
            \DB::rollback();
            return echoErr();
        }
        \DB::commit();

        //记录日志
        \Event::fire(new AdminLogEvent($adminUserData));

        return echoOk();
    }


    /**
     * 修改账号状态
     * User: fly
     */
    public function status()
    {
        $fromErr = $this->validatorFrom([
            'id' => 'required|Integer',
            'param.admin_user_status' => 'required|Integer',
        ]);
        if($fromErr){
            return $fromErr;
        }
        $id  = Input::get('id');
        $status = Input::get('param.admin_user_status');

        $status = AdminUser::statusData($id,$status);

        if($status['ok'] == 2){
            return echoErr();
        }
        //记录日志
        \Event::fire(new AdminLogEvent($status['original']));

        return echoOk();
    }

    /**
     * 修改账号角色
     * User: fly
     */
    public function roleStatus()
    {
        $fromErr = $this->validatorFrom([
            'id' => 'required|Integer',
            'param.admin_role_id' => 'required|Integer',
        ]);
        if($fromErr){
            return $fromErr;
        }
        $id  = Input::get('id');
        $adminRoleId = Input::get('param.admin_role_id');

        $status = AdminUser::upRoleData($id,$adminRoleId);

        if($status['ok'] == 2){
            return echoErr();
        }
        //记录日志
        \Event::fire(new AdminLogEvent($status['original']));

        return echoOk();
    }
}
