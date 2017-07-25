<?php

namespace App\Http\Controllers\Backstage;



use App\Events\AdminLogEvent;
use App\Http\Models\Backstage\AdminAccess;
use App\Http\Models\Backstage\AdminNote;
use App\Http\Models\Backstage\AdminRole;
use App\Http\Models\Backstage\AdminUser;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;


class AdminNoteController extends Controller
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
            array('name'=>'节点管理','url'=>URL::route('admin_role_index'))
        );
        //设置标题
        $this->topTitle = '节点列表';
        //设置搜索条件和参数
        $data = AdminNote::queryNoteArray([['admin_admin_show','=','0']]);
        $data = $this->getNoteData($data);

        //输出界面
        return $this->showView('Backstage.AdminNote.index',['data'=>$data]);
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
                'param.admin_note_name'=>'required',
                'param.admin_note_zh_name'=>'required',
                'param.admin_note_default'=>'required',
                'param.admin_box'=>'required',
                'param.admin_note_controller'=>'required',
                'param.admin_note_action'=>'required',
                'param.admin_admin_show'=>'required',
                'param.admin_note_parent_id'=>'required',
                'param.admin_note_sort'=>'required',
                'param.admin_note_show'=>'required',
                'param.admin_has_subset'=>'required',

            ],array());
            if($fromErr){//输出表单验证错误信息
                return $fromErr;
            }
            $param = Input::get('param');


            $status = AdminNote::saveData($id,$param);//新增修改数据

            if($status['ok'] == 2){

                return echoErr($status['msg']);
            }

            //记录日志
            \Event::fire(new AdminLogEvent($status['original']));

            return echoOk();
//          return redirect('message')->with(array('title'=>'操作失败','type'=>2,'url'=>'','wait'=>5,'msg'=>$fromErr));
        }
        $str = '添加节点';
        if($id){
            $str = '修改节点';
        }
        //设置面包屑
        $this->breadcrumb = array(
            array('name'=>'节点管理','url'=>URL::route('admin_role_index')),
            array('name'=>$str,'url'=>'')
        );
        //设置标题
        $this->topTitle = $str;

        $data = AdminNote::findOrNew($id);


        //输出界面
        return $this->showView('Backstage.AdminNote.save',array('data'=>$data));
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

        $adminUserData = AdminNote::find($id);

        if(!$adminUserData ){
            return echoErr('节点不存在');
        }

        $status = AdminNote::delData($id);

        if($status['ok'] == 2){

            return echoErr();
        }


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
