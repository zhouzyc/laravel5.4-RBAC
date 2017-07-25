<?php

namespace App\Http\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;


class User extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    // 软删除和用户验证attempt
    use SoftDeletes,Authenticatable, Authorizable, CanResetPassword;


    protected $table = 'user';

    protected $primaryKey = 'id';


    public function getAuthIdentifier(){
        return $this->id;
    }
    public function getAuthPassword()
    {
        return $this->password;
    }

    protected function getDateFormat()
    {
        return 'U';
    }

    // jwt 需要实现的方法
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // jwt 需要实现的方法
    public function getJWTCustomClaims()
    {
        return [];
    }
}
