<?php
/**
 * Created by PhpStorm.
 * User: fly
 * Date: 16/9/27
 * Time: 14:36
 */
namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Models\Api\User;
use Illuminate\Http\Request;
use App\Http\Transformer\UserTransformer;
use Illuminate\Support\Facades\Input;



class LoginController extends ApiController
{






    public function index()
    {

        $user = \Auth::user();

        $user = User::paginate(1);


        return $this->echoApiOk($this->response->paginator($user,new UserTransformer())->original);

    }

    public function login(Request $request)
    {
        //验证表单提交参数
        $fromErr = $this->validatorFrom([
            'mail' => 'required|email',
            'password' => 'required',
        ],array());
        if($fromErr){
            return $fromErr;
        }

        $credentials = $request->only('mail', 'password');

        // 验证失败返回403
        if (! $token = \JWTAuth::attempt($credentials)) {

            $this->response->errorForbidden(trans('auth.incorrect'));
        }

        return $this->echoApiOk(compact('token'));
    }

    /**
     * @api {post} /auth/token/new 刷新token(refresh token)
     * @apiDescription 刷新token(refresh token)
     * @apiGroup Auth
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiHeader {String} Authorization 用户旧的jwt-token, value已Bearer开头
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL21vYmlsZS5kZWZhcmEuY29tXC9hdXRoXC90b2tlbiIsImlhdCI6IjE0NDU0MjY0MTAiLCJleHAiOiIxNDQ1NjQyNDIxIiwibmJmIjoiMTQ0NTQyNjQyMSIsImp0aSI6Ijk3OTRjMTljYTk1NTdkNDQyYzBiMzk0ZjI2N2QzMTMxIn0.9UPMTxo3_PudxTWldsf4ag0PHq1rK8yO9e5vqdwRZLY"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         token: 9UPMTxo3_PudxTWldsf4ag0PHq1rK8yO9e5vqdwRZLY.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL21vYmlsZS5kZWZhcmEuY29tXC9hdXRoXC90b2tlbiIsImlhdCI6IjE0NDU0MjY0MTAiLCJleHAiOiIxNDQ1NjQyNDIxIiwibmJmIjoiMTQ0NTQyNjQyMSIsImp0aSI6Ijk3OTRjMTljYTk1NTdkNDQyYzBiMzk0ZjI2N2QzMTMxIn0.eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9
     *     }
     */
    public function refreshToken()
    {
        $token = \JWTAuth::refresh();

        return $this->echoApiOk(compact('token'));
    }


    public function register()
    {

        //验证表单提交参数
        $fromErr = $this->validatorFrom([
            'email' => 'required|email',
            'password' => 'required',
        ],array());
        if($fromErr){
            return $fromErr;
        }

        $email = Input::get('email');

        $password = Input::get('password');

        $model =   new User();
        $model->mail = $email;
        $model->pass = app('hash')->make($password);
        $model->save();

        $token = \JWTAuth::fromUser($model);


        return $this->echoApiOk(compact('token'));
    }
}