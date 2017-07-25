<?php

namespace App\Http\Controllers\Backstage;


use App\Http\Models\Backstage\AdminNote;
use App\Http\Models\Backstage\AdminUser;
use App\Http\Models\MyMongodb;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

class Controller extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $error;
    //网页title
    public  $title = '后台管理';
    //用户登录信息
    public  $userData = array();
    //设置面包屑
    public  $breadcrumb = array();
    //top标题
    public $topTitle = '';
    //搜索参数
    public $param= array();

    //日志表名
    public $adminLogTitle = '';



    /**
     * 6点
     * 验证登录
     */
    function __construct()
    {

        $this->middleware(['auth','role']);

        if(\Session::get('lockScreen')){
            self::redirectUrl('/lockscreen');
        }
    }


    /**
     * 验证表单
     * Web:2.0 版本
     * User: fly
     * @param       $rule
     * @param array $mes
     *$type = true 直接返回字符串信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function  validatorFrom($rule,$mes = array(),$isFrom = false)
    {
        $defaultMessages = [
            'required'  => ':attribute 参数不存在',
            'email'     => '邮箱不正确',
            'unique'    => ':attribute 已被占用',
            'alpha_dash'=> ':attribute 有不允许字符',
            'max'       => ':attribute 超过最大限制',
            'confirmed'=>'两次密码不一致'
        ];
        $messages = array_merge($defaultMessages, $mes);

        $validator = \Validator::make(Input::all(),$rule,$messages);

        if($validator->fails()){
            $messages = $validator->errors();
            foreach ($messages->all() as $msg) {
                if($isFrom){
                    return ($msg);
                }else{
                    return echoErr($msg);
                }
            }
        }
        return false;
    }
    /**
     * 跳转到指定页面
     * Web:2.0 版本
     * User: fly
     * @param $url
     */
    public function redirectUrl($url)
    {
        header("location:".$url);
        exit();
    }
    /**
     * 渲染模板
     * User: 6点
     * @param       $view
     * @param array $data
     * @param array $mergeData
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showView($view,$data = array(),$mergeData = [])
    {
        view()->share('title',$this->title);
        $this->userData = \Auth::user();
        if(!empty($this->userData)){
            view()->share('userData',$this->userData);
            view()->share('param',$this->param);
            view()->share('noteHtml',self::setNavigation());
            view()->share('breadcrumbHtml',self::setBreadcrumbHtml());
            view()->share('topTitleHtml',self::setTopTitleHtml());
            if(env('ADMIN_LOG','true')){
                self::setAdminLog();
            }

        }
        return view($view,$data,$mergeData);
    }


    public function setAdminLog(){


        if(!$this->adminLogTitle){

            $controller = \Route::current()->getName();

        }else{
            $controller = $this->adminLogTitle;
        }


        return $this->queryAdminLog($controller);

    }

    /**
     * 操作日志
     * Web:2.0 版本
     * User: fly
     * @param $controller
     * @param $str
     */
    public function queryAdminLog($controller,$page = 1,$limit = 10){

        $start = setPage($page,$limit);

        $losType = env('LOG_TYPE', 'mysql');

        if($losType == 'mysql'){

            $data =DB::table('admin_log')
                ->skip($start)
                ->take($limit)
                ->orderBy('created_time','desc')
                ->where('admin_log_controller','=',$controller)
                ->get();
            $count = DB::table('admin_log')
                ->where('admin_log_controller','=',$controller)
                ->count();
            $list = array();

            if($data){
                foreach ($data as $val){
                    $list[] = array(
                        'name'=>$val->admin_user_name,
                        'msg'=>$val->admin_log_msg,
                        'date'=>array('date'=>date('Y-m-d H:i:s',$val->created_time)),
                    );
                }
            }

        }else{

            $mongo = new \App\Http\Models\MyMongodb(config('database.mongodb.host').':'.config('database.mongodb.port'));
            $mongo->selectDb(config('database.mongodb.database'));
            $list = $mongo->find(
                $controller,
                array(),
                array("start"=>$start,"limit"=>$limit,'sort'=>array("date"=>-1))
            );

            $count = $mongo->count($controller);
        }



        view()->share('adminPage',getPage($page,$count,$limit,$controller));

        view()->share('adminLog',$list);

        return true;
    }
    /**
     * 设置列表顶部标题
     * Web:2.0 版本
     * User: fly
     */
    public function setTopTitleHtml()
    {
        if($this->topTitle){
            return '<h2>'.$this->topTitle.'</h2>';
        }
        return '';
    }
    /**
     * 设置面包屑
     * Web:2.0 版本
     * User: fly
     * @param $data
     */
    public function setBreadcrumbHtml()
    {
        $html = '';
        if(!empty($this->breadcrumb)){
            $end = count($this->breadcrumb);
            foreach($this->breadcrumb as $key=>$breadcrumb){
                if(($key+1) == $end){
                    if($key == 0){
                        $html .= '<li class="active" >'.$breadcrumb['name'].'</li>';
                    }else{
                        $html .= '<li class="active" >'.$breadcrumb['name'].'</li>';
                    }
                }elseif($key == 0){
                    $html .= '<li><a href="'.$breadcrumb['url'].'">'.$breadcrumb['name'].'</a></li>';
                }else{
                    $html .= '<li><a href="'.$breadcrumb['url'].'">'.$breadcrumb['name'].'</a></li>';
                }
            }
        }
        return $html;
    }
    /**
     * 生成导航栏
     * Web:2.0 版本
     * User: fly
     * @param $show
     */
    public function setNavigation()
    {

        if($this->userData['admin_user_admin'] != 1){
            $data = AdminUser::queryUserNoetArray($this->userData['admin_user_id']);
            $noteData = $data['access']['role']['role_user'];
        }else{
            $noteData =  AdminNote::queryNoteArray([]);

        }
        $noteData = $this->getNoteData($noteData);
        $noteHtml = $this->getNoteHtml($noteData);

        return $noteHtml;
    }

    /**
     * 得到用户的导航节点
     * Web:2.0 版本
     * User: fly
     * @param $data
     */
    public function getNoteData($data)
    {
        if(empty($data)){
            return array();
        }
        $noteData = array();
        $this->userData = \Auth::user();
        if($this->userData['admin_user_admin'] != 1){

            foreach($data as $key=>$val){
                if($val['note']['admin_note_show'] == 0){
                    continue;
                }
                if($val['note']['admin_note_parent_id'] == 0){
                    $noteData[$key] = $val['note'];
                    foreach($data as $vo){
                        if($vo['note']['admin_note_show'] == 0){
                            continue;
                        }
                        if($vo['note']['admin_note_parent_id'] == $val['note']['admin_note_id'] && $vo['note']['admin_note_id'] != $val['note']['admin_note_id']){
                            $noteData[$key]['children'][] = $vo['note'];
                        }
                    }
                }
            }
        }else{
            foreach($data as $key=>$val){
                if($val['admin_note_parent_id'] == 0){
                    $noteData[$key] = $val;
                    foreach($data as $vo){
                        if($vo['admin_note_parent_id'] == $val['admin_note_id'] && $vo['admin_note_id'] != $val['admin_note_id']){
                            $noteData[$key]['children'][] = $vo;
                        }
                    }
                }
            }
        }

        return $noteData;
    }

    /**
     * 得到导航html
     * Web:2.0 版本
     * User: fly
     * @param $data
     */
    public function getNoteHtml($data)
    {
        if(empty($data)){
            return array();
        }

        $noteHtml = array();

        $icons = array(
                'fa fa-bell',
                'fa fa-calendar-o',
                'fa fa-bar-chart-o',
                'fa fa-coffee',
                'fa fa-cloud',
                'fa fa-envelope-o',
                'fa fa-dashboard',
                'fa fa-desktop',
                'fa fa-flask',
                'fa fa-gear',
                'fa fa-gears',
                'fa fa-signal',
                'fa fa-bell',
                'fa fa-calendar-o',
                'fa fa-bar-chart-o',
                'fa fa-coffee',
                'fa fa-cloud',
                'fa fa-envelope-o',
                'fa fa-dashboard',
                'fa fa-desktop',
                'fa fa-flask',
                'fa fa-gear',
                'fa fa-gears',
                'fa fa-signal'
            );
        $iconsNumber = 0;
        foreach($data as $key=>$val){
                 $getName = \Route::current()->getName();

                 $noteHtml[$key]['html'] = '';

                 $ioOpen = 0;

                 if(!empty($val['children'])){


                    foreach($val['children'] as $children){
//                        var_dump($children);
                        if($getName == $children['admin_note_controller']){
                            $ioOpen = 1;
                        }
                    }

                 }else{

                     if($getName == $val['admin_note_controller'] || $getName == $val['admin_note_action']){
                         $ioOpen = 1;
                     }

                 }

                 if($ioOpen == 1){

                       $noteHtml[$key]['html'].= ' <li class="active"> ';

                 }else{

                       $noteHtml[$key]['html'].= ' <li class=""> ';

                 }
    //
                 if(!empty($val['children'])){


                     $noteHtml[$key]['html'].= '    <a href="#" > ';
                     $noteHtml[$key]['html'].= '         <i class="'.$icons[$iconsNumber].'"></i>';
                     $noteHtml[$key]['html'].= '	     <span class="nav-label"> '.$val['admin_note_name'].' </span> ';
                     $noteHtml[$key]['html'].= '	     <span class="fa arrow"></span> ';
                     $noteHtml[$key]['html'].= '	</a> ';

                     if( $ioOpen == 1){
                         $noteHtml[$key]['html'].= '	<ul class="nav nav-second-level collapse in" > ';
                     }else{
                         $noteHtml[$key]['html'].= '	<ul class="nav nav-second-level collapse" > ';
                     }

                     foreach($val['children'] as $children){

                         if($children['admin_note_show'] == 1){


                             if( $getName == $children['admin_note_controller'] || mb_strpos($children['admin_note_action'],$getName) !==false  ){

                                 $noteHtml[$key]['html'].='       <li class="active">';

                             }else{

                                 $noteHtml[$key]['html'].='       <li>';

                             }
//
                             $noteHtml[$key]['html'].='         <a href="'.URL::route($children['admin_note_controller']).'"> ';
                             $noteHtml[$key]['html'].='	                '.$children['admin_note_name'].'';
                             $noteHtml[$key]['html'].='         </a>';
                             $noteHtml[$key]['html'].='       </li> ';
                         }

                     }

                     $noteHtml[$key]['html'].= '</ul></li> ';

                 }else{

                     $noteHtml[$key]['html'].='     <a href="'.URL::route($val['admin_note_controller']).'"> ';
                     $noteHtml[$key]['html'].='       <i class="'.$icons[$iconsNumber].'"></i> ';
                     $noteHtml[$key]['html'].='       <span class="nav-label"> '.$val['admin_note_name'].' </span> ';
                     $noteHtml[$key]['html'].=' 	</a>';
                     $noteHtml[$key]['html'].='</li>';
                 }
            $iconsNumber++;
        }
        return $noteHtml;
    }


    /**
     * 操作日志
     * Web:2.0 版本
     * User: fly
     * @param $controller
     * @param $str
     */
    public function setEventAdminLog($controller,$str){

        $losType = env('LOG_TYPE', 'mysql');

        if($losType == 'mysql'){


            $data['admin_user_id'] = \Auth::user()->admin_user_id;
            $data['admin_user_name'] = \Auth::user()->admin_user_name;
            $data['admin_log_controller'] = $controller;
            $data['admin_log_msg'] = $str;
            $data['created_time'] = time();

            DB::table('admin_log')->insert($data);


        }else{
            $mongo = new \App\Http\Models\MyMongodb(config('database.mongodb.host').':'.config('database.mongodb.port'));
            $mongo->selectDb(config('database.mongodb.database'));

            $r = $mongo->insert(
                $controller,
                array(
                    "id"=>\Auth::user()->admin_user_id,
                    "name"=>\Auth::user()->admin_user_name,
                    "msg"=>$str,
                    'date'=>\Carbon\Carbon::now()
                )
            );
        }



    }
    /**
     * 获取当前控制器与方法
     *
     * @return array
     */
    public function getCurrentAction()
    {
        $action = \Route::current()->getActionName();
        list($class, $method) = explode('@', $action);

        return ['controller' => $class, 'method' => $method];
    }


    /**
     * 验证用户权限
     * User: fly
     */
    public function verifyAuth($controller = false,$action = false){

        if(!$controller){
            $controller = \Route::current()->getName();
        }

        $userData = \Auth::user();

        if(!$userData){
            return false;
        }
        $noArray = [
            'admin_index',
            'admin_index_upuserdata',
            'admin_message'
        ];
        //你跳转直接过
        if(in_array($controller,$noArray)){
            return true;
        }
        if($userData['admin_user_admin'] == 1){
            return true;
        }


        $where[] = ['admin_note_controller','=',$controller];

        $dataAdminNote = AdminNote::findWhereFirst($where);

        if(!$dataAdminNote){
            return false;
        }
        $data = AdminUser::queryUserNoteWhereArray(array('admin_user_id'=>$userData['admin_user_id']));

        if(!$data || empty($data['access']['role']['role_user'])){
            return false;
        }
        $isAuth = 0;
        foreach($data['access']['role']['role_user'] as $note){

            if($note['admin_note_id'] == $dataAdminNote['admin_note_id']){
                $isAuth = 1;
            }
        }
        if($isAuth == 0){
            return false;
        }

        return true;

    }

}
