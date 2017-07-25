<?php

namespace App\Http\Controllers\Api;


use Dingo\Api\Routing\Helpers;


use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;

class ApiController extends BaseController
{


    use Helpers;

    public $userData = [];
    /**
     * 6点
     * 验证登录
     */
    function __construct()
    {

        \Auth::setDefaultDriver('api') ;

        $this->userData = \Auth::user();

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


    public function echoApiErr($msg = '请求失败',$code = '100001',$status = 0){
        $data = array(
            'status'=>$status,
            'data'=>(object)array(),
            'msg'=>$msg,
            'code'=>$code,
        );
        $this->response->array($data);
    }

    public function echoApiOk($data = array(),$status = 1){
        $data = array(
            'status'=>$status,
            'data'=>(object)$data,
            'msg'=>'',
            'code'=>0,
        );
        return $this->response->array($data);
    }



}
