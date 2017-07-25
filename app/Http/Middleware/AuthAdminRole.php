<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Backstage\Controller;
use App\Http\Models\Backstage\AdminNote;
use App\Http\Models\Backstage\AdminUser;
use Closure;


class AuthAdminRole
{


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $model = new Controller();
        $isAuth = $model->verifyAuth();
        if(!$isAuth){
            return response('没有权限.', 401);
        }
        return $next($request);
    }
}
