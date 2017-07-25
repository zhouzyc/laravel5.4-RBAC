<?php

namespace App\Http\Controllers\Backstage;



use App\Http\Models\Backstage\AdminResetPassword;
use App\Http\Models\Backstage\AdminUser;
use App\Http\Models\PhoneCode;
use App\Http\Models\Users;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{

    use AuthenticatesUsers;



    protected $redirectTo = '/Index/index.html';

    protected $guard = 'admin';

    //错误次数
    public $maxLoginAttempts = 5;

    function __construct()
    {
        $this->middleware('guest:admin', ['except' => 'logout']);

    }




    //第三方
    public function redirectToProvider(Request $request,$service)
    {

        return Socialite::driver($service)->redirect();
    }
    //第三方
    public function handleProviderCallback(Request $request,$service)
    {
        $user = Socialite::driver($service)->user();
        dd($user);
    }

    /**
     * 登录界面
     * User: 6点
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //输出界面
        $this->title = '后台登录';

        return $this->showView('Backstage.Login.login');
    }

    /**
     * 登录接口
     * User: 6点
     * @param Request $request
     */
    public function login(Request $request)
    {

        $fromErr = $this->validatorFrom([
            'account' => 'required|alpha_dash|max:50',
            'password' => 'required|alpha_dash|max:50',
            'logincode'=>'required|alpha_dash|max:6',
        ]);
        if($fromErr){
            return $fromErr;
        }
        $username = Input::get('account') ;
        $password = Input::get('password') ;
        $logincode = Input::get('logincode') ;
//        $remember = Input::get('remember') ;

        if(\Session::pull('milkcaptcha') != $logincode) {
            return echoErr('验证码错误','600001');
        }

        $adminUser = AdminUser::findWhere(['admin_user_account'=>$username]);

        if(!$adminUser || $adminUser['admin_user_status'] == 2){
            return echoErr('账号不存在或者被禁止使用!');
        }

        if ($lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return echoErr('错误次数过多，请稍后再试!');
        }

        $r = \Auth::attempt(['admin_user_account'=>$username,'password'=>$password]);

        if(!$r){
            if (! $lockedOut) {
                $this->incrementLoginAttempts($request);
            }
            return echoErr('用户密码错误');
        }


        return echoOk();
    }

    /**
     * 退出登录
     * Web:2.0 版本
     * User: fly
     * @return \Illuminate\Http\JsonResponse\
     */
    public function quit()
    {

        Auth::logout();

        return echoOk();
    }

    /**
     * 找回验证码发送界面
     * Web:2.0 版本
     * User: fly
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function forgotPass()
    {
        //输出界面
        $this->title = '找回密码';

        if($_POST){
            $fromErr = $this->validatorFrom([
                'pass'=>'required|alpha_dash|max:50',
                'cpass'=>'required|alpha_dash|max:50',
                'phone'=>'required|max:11',
                 'code'=>'required|max:6',
            ]);
            if($fromErr){
                return $fromErr;
            }

            $yphone = Input::get('pass');
            $nphone = Input::get('cpass');
            if($yphone != $nphone){
                return echoErr('密码不一致');
            }
            $phone = Input::get('phone');
            $code = Input::get('code');

            $r = PhoneCode::isSend([['phone','=',$phone],['code','=',$code],['type','=',1],['valid_time','>',time()]]);
            if(!$r){
                return echoErr('验证码错误');
            }

            $user = AdminUser::where('admin_user_phone','=',$phone)->first();
            if(!$user){
                return echoErr('验证码错误');
            }

            $user->admin_user_password =  bcrypt($yphone);
            $user->save();

            return echoOk();
        }


        return $this->showView('Backstage.Login.forgotPassword');
    }

    /**
     * 设置密码
     * Web:2.0 版本
     * User: fly
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function changePhone()
    {

        $fromErr = $this->validatorFrom([
            'id'=>'required|Integer',
        ],array(),true);
        if($fromErr){
            return redirect('message')->with(array('title'=>'操作失败','type'=>2,'url'=>'','wait'=>5,'msg'=>$fromErr));
        }
        $id = Input::get('id');
        //输出界面
        $this->title = '更换手机';

        if($_POST){
            $fromErr = $this->validatorFrom([
                'yphone'=>'required|Integer',
                'nphone'=>'required|Integer',
                'phone'=>'required'
            ]);
            if($fromErr){
                return $fromErr;
            }

            $yphone = Input::get('yphone');
            $nphone = Input::get('nphone');
            $phone = Input::get('phone');


            $user = \DB::table('admin_user')->where('admin_user_id','=',$id)->first();
            if(!$user){
                return echoErr('用户不存在');
            }
            $r = PhoneCode::isSend([['phone','=',$user->admin_user_phone],['code','=',$yphone],['type','=',2],['valid_time','>',time()]]);
            if(!$r){
                return echoErr('验证码错误');
            }

            $r = PhoneCode::isSend([['phone','=',$phone],['code','=',$nphone],['type','=',3],['valid_time','>',time()]]);
            if(!$r){
                return echoErr('验证码错误');
            }

            $user->admin_user_phone =  $phone;
            $user->save();

            return echoOk();
        }


        return $this->showView('Backstage.Login.changePhone',['data'=>AdminUser::findOne($id)]);
    }


    public function forgotPassMail()
    {
        //输出界面
        $this->title = '找回密码';



        if($_POST){
            $fromErr = $this->validatorFrom([
                'mail'=>'required|email|max:50',
            ]);
            if($fromErr){
                return $fromErr;
            }

            $mail = Input::get('mail');



            $resetRoken = md5(csrf_token().(time()));

            $model = AdminResetPassword::addData($mail,$resetRoken);

            if(!$model){
                return echoErr('发送错误');
            }

            $flag = \Mail::send('Backstage.Login.password',['resetRoken'=>$resetRoken],function($message)use($mail){
                $to = $mail;
                $message ->to($to)->subject('重置密码');
            });

            return echoOk();
        }


        return $this->showView('Backstage.Login.forgotPasswordMail');
    }

    public function resetPass(){
        //输出界面
        $this->title = '重置密码';

        $token = Input::get('token');

        if($_POST){
            $fromErr = $this->validatorFrom([
                'pass'=>'required|alpha_dash|max:50',
                'cpass'=>'required|alpha_dash|max:50',
                'token'=>'required|alpha_dash|max:200',
            ]);
            if($fromErr){
                return $fromErr;
            }

            $pass = Input::get('pass');
            $cpass = Input::get('cpass');

            if($pass != $cpass){
                return echoErr('密码不一致');
            }

            $data = AdminResetPassword::findTokenData($token);

            if(!$data){
                return echoErr('修改失败!');
            }
            $user = AdminUser::findEmailData($data['admin_user_email']);

            if(!$user){
                return echoErr('账号不存在!');
            }
            $user->admin_user_password = bcrypt($pass);

            $r = $user->save();

            if(!$r){
                return echoErr('修改失败');
            }

            $data->delete();

            return echoOk();
        }


        return $this->showView('Backstage.Login.resetpass',['token'=>$token]);
    }
}
