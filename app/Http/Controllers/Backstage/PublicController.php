<?php

namespace App\Http\Controllers\Backstage;



use App\Http\Models\PhoneCode;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\NumberPhraseBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;



class PublicController extends Controller
{


    function __construct()
    {

    }


    public function kernel()
    {

//        Kernel::legend();

    }
    public function sms()
    {
        $fromErr = $this->validatorFrom([
            'phone' => 'required|alpha_dash',
            'code' => 'required|alpha_dash',
            'type' => 'required',
        ]);
        if($fromErr){
            return $fromErr;
        }
        $phone = Input::get('phone');
        $code = Input::get('code');
        $type = Input::get('type');

        if(\Session::get('milkcaptcha') != $code) {
            return echoErr('验证码错误','600001');
        }
//        15308047727
        $user = \DB::table('admin_user')->where('admin_user_id','=',$phone)->first();
        if(!$user){
            return echoErr('发送失败','600001');
        }

        if($type == 2){
            $phone = $user->admin_user_phone;
        }


        $data = PhoneCode::isSend([['phone','=',$phone],['type','=',$type],['valid_time','>',time()]]);

        if($data){
            return echoOk();
        }

        $pcode = rand(0,999999);


        if(!sms($phone,$pcode)){
            return echoErr('发送失败','600001');
        }

        $status = PhoneCode::addData($phone,$pcode,$type);

        if($status['ok'] == 2){
            return echoErr('发送失败','600001');
        }


        return echoOk();

    }
    /**
     * 屏幕锁
     * Web:2.0 版本
     * User: fly
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lockScreen(Request $request)
    {
        if($_POST){

            $user = \Auth::user();

            $fromErr = $this->validatorFrom([
                'password' => 'required|alpha_dash|max:50',
            ]);
            if($fromErr){
                return $fromErr;
            }

            $password =  Input::get('password');
            $r = \Auth::attempt(['admin_user_account'=>$user['admin_user_account'],'password'=>$password],false,false);
            if($r){
                \Session::forget('lockScreen');
            }else{
                return echoErr('密码不正确');
            }
            return echoOk();
        }
        return view('Backstage.errors.lockscreen',['userData'=>\Auth::user(),'title'=>'屏幕锁']);
    }

    /**
     * 屏幕枷锁
     * Web:2.0 版本
     * User: fly
     * @return \Illuminate\Http\JsonResponse
     */
    public function lock()
    {
        \Session::put('lockScreen',1);

         return echoOk();
    }

    /**
     * 上传图片
     * Web:2.0 版本
     * User: fly
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadimg(Request $request)
    {
        $file = $request->file('file');
        if($file -> isValid()){
            $allowed_extensions = ["png", "jpg", "gif"];
            if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {
                return echoErr('类型错误');
            }
            $clientName = $file->getClientOriginalName();
            $day = date('Y-m-d');
            $destinationPath = 'uploads/images/'.$day.'/';
            $extension = 'png';
            $fileName = md5(date('ymdhis').microtime().mt_rand(1,10000).$clientName).".".$extension;
            $file->move($destinationPath, $fileName);
            return echoOk(array('src'=>asset($destinationPath.$fileName)));
        }
        return echoErr('上传失败');
    }

    /**
     * 信息提示页面
     * Web:2.0 版本
     * User: fly
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function prompt()
    {
        parent::__construct();
        return view('Backstage.errors.message');
    }

    /**
     * 验证码
     * Web:2.0 版本
     * User: fly
     */
    public function code(Request $request)
    {

        require_once APP_PATH.'/resources/org/Captcha/autoload.php';
        //生成验证码图片的Builder对象，配置相应属性
        //生成验证码图片的Builder对象，配置相应属性
        $numberBuilder = new NumberPhraseBuilder();

        $builder = new CaptchaBuilder(null, $numberBuilder);
        //可以设置图片宽高及字体
//        $builder = new CaptchaBuilder;
        //可以设置图片宽高及字体
        $builder->build($width = 100, $height = 40, $font = null);
        //获取验证码的内容
        $phrase = $builder->getPhrase();
        //把内容存入session
        \Session::put('milkcaptcha', $phrase);

        //生成图片
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        $builder->output();
    }


    public function adminlog()
    {
        $page = Input::get('page');
        $name = Input::get('name');

        $model = new Controller();
        $model->queryAdminLog($name,$page);

        return view('Backstage.Layouts.log');
    }


}
