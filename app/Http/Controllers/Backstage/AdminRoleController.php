<?php

namespace App\Http\Controllers\Backstage;


use App\Events\AdminLogEvent;
use App\Http\Models\Backstage\AdminNote;
use App\Http\Models\Backstage\AdminRole;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;

class AdminRoleController extends Controller
{


    /**
     * 得到角色列表
     * Web:2.0 版本
     * User: fly
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {

        //获取表单参数
        $param = Input::get('param') ;
        //注册参数到界面
        $this->param = $param ;
        //设置面包屑
        $this->breadcrumb = array(
            array('name'=>'角色管理','url'=>URL::route('admin_note_index'))
        );
        //设置标题
        $this->topTitle = '角色列表';
        //设置搜索条件和参数
        $roleData = AdminRole::queryData($param);

        //输出界面
        return $this->showView('Backstage.AdminRole.index',['data'=>$roleData]);
    }

    /**
     * 新增修改数据
     * Web:2.0 版本
     * User: fly
     */
    public function save()
    {

        //获取表单参数
        $id = Input::get('id') ;

        if($_POST){
            $fromErr = $this->validatorFrom([
                'param.admin_role_name'=>'required',
                'param.admin_role_status'=>'required',
            ],array());
            if($fromErr){
                return $fromErr;
            }
            //获取表单参数
            $param = Input::get('param') ;
            //得到原来的数据
            \DB::beginTransaction();
            $status = AdminRole::saveData($id,$param);
            if($status['ok'] == 2){
                \DB::rollback();
                return echoErr($status['msg']);
            }
            \DB::commit();

            //记录日志
            \Event::fire(new AdminLogEvent($status['original']));

            return echoOk();
        }
        $str = '添加角色';
        if($id){
            $str = '修改角色';
        }
        //设置面包屑
        $this->breadcrumb = array(
            array('name'=>'角色管理','url'=>URL::route('admin_note_index')),
            array('name'=>$str,'url'=>''),
        );
        //设置标题
        $this->topTitle = $str;

        $data = AdminRole::findRoleUserOrNew($id);

        $noteData = AdminNote::queryNoteArray(array('admin_admin_show'=>0));

        $noteData = parent::getNoteData($noteData);


        //输出界面
        return $this->showView('Backstage.AdminRole.save',array('data'=>$data,'noteData'=>$noteData));
    }
    /**
     * 删除角色
     * Web:2.0 版本
     * User: fly
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function del()
    {
        $fromErr = $this->validatorFrom([
            'id' => 'required|Integer',
        ]);
        if($fromErr){
            return $fromErr;
        }
        $id  = Input::get('id');

        $adminRoleData = AdminRole::findOne($id);

        if(!$adminRoleData){
            return echoErr('角色不存在');
        }
        \DB::beginTransaction();
        $status = AdminRole::delData($id);
        if($status['ok'] == 2){
            \DB::rollback();
            return echoErr();
        }
        \DB::commit();

        //记录日志
        \Event::fire(new AdminLogEvent($adminRoleData));

        return echoOk();
    }

    /**
     * 修改角色状态
     * User: fly
     */
    public function status()
    {
        $fromErr = $this->validatorFrom([
            'id' => 'required|Integer',
            'param.admin_role_status' => 'required|Integer',
        ]);
        if($fromErr){
            return $fromErr;
        }
        $id  = Input::get('id');
        $status = Input::get('param.admin_role_status');

        $status = AdminRole::statusData($id,$status);
        if($status['ok'] == 2){
            return echoErr();
        }
        //记录日志
        \Event::fire(new AdminLogEvent($status['original']));

        return echoOk();
    }
}
