<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['middleware' => ['admin']], function () {


//----------------第三方登陆----------------------
//    Route::get('auth/{service}', 'LoginController@redirectToProvider');
//    Route::get('auth/{service}/callback', 'LoginController@handleProviderCallback');
//----------------操作成功失败返回页面----------------------


//----------------操作成功失败返回页面----------------------

    Route::get('/kernel','Backstage\PublicController@kernel')->name('admin_kernel');

    Route::post('/send/sms.html','Backstage\PublicController@sms')->name('admin_send_sms');

    Route::get('/message','Backstage\PublicController@prompt')->name('admin_message');
    Route::get('/code/{tmp}','Backstage\PublicController@code')->name('admin_code');
    Route::post('/upload/img', 'Backstage\PublicController@uploadimg')->name('admin_upload_img');
    //屏幕锁
    Route::any('/lockscreen', 'Backstage\PublicController@lockScreen')->name('admin_lockscreen');
    Route::post('/lock', 'Backstage\PublicController@lock')->name('admin_lock');
    //adminlog分页
    Route::post('/upload/adminlog', 'Backstage\PublicController@adminlog')->name('admin_upload_adminlog');

//----------------操作成功失败返回页面----------------------



//登录登录后首页
    Route::get('Index/index.html', 'Backstage\IndexController@index')->name('admin_index');
    Route::any('Index/upuserdata.html', 'Backstage\IndexController@save')->name('admin_index_upuserdata');
    //默认进来进入登录页
    Route::get('/', function()
    {
        
    });

//----------------登录----------------------
    Route::group(['prefix'=>'Login'], function () {
        //登录页页面
        Route::get('/index.html', 'Backstage\LoginController@index')->name('admin_login_index');
        //退出登录
        Route::post('/quit.html', 'Backstage\LoginController@quit')->name('admin_login_quit');
        //登录操作
        Route::post('/login.html', 'Backstage\LoginController@login')->name('admin_login_login');

        Route::match(array('post','get'),'/changephone.html', 'Backstage\LoginController@changePhone')->name('admin_login_changephone');

        Route::match(array('post','get'),'/forgotpass.html', 'Backstage\LoginController@forgotPass')->name('admin_login_forgotpass');

        Route::match(array('post','get'),'/forgotpassmail.html', 'Backstage\LoginController@forgotPassMail')->name('admin_login_forgotpassmail');

        Route::match(array('post','get'),'/resetpass.html', 'Backstage\LoginController@resetPass')->name('admin_login_resetpass');
    });

//----------------登录----------------------

//----------------角色----------------------

    Route::group(['prefix'=>'AdminRole'], function () {
        //角色管理列表
        Route::get('/index.html', 'Backstage\AdminRoleController@index')->name('admin_role_index');
        //角色编辑创建
        Route::match(array('post','get'),'/save.html', 'Backstage\AdminRoleController@save')->name('admin_role_save');
        //角色删除
        Route::post('/del.html', 'Backstage\AdminRoleController@del')->name('admin_role_del');
        //角色删除
        Route::post('/status.html', 'Backstage\AdminRoleController@status')->name('admin_role_status');
    });

//----------------角色----------------------

//----------------账号管理-------------------------
    Route::group(['prefix'=>'AdminUser'], function () {
        Route::get('/index.html', 'Backstage\AdminUserController@index')->name('admin_user_index');
        //角色编辑创建
        Route::match(array('post','get'),'/save.html', 'Backstage\AdminUserController@save')->name('admin_user_save');
        //角色删除
        Route::post('/del.html', 'Backstage\AdminUserController@del')->name('admin_user_del');
        //账号删除
        Route::post('/status.html', 'Backstage\AdminUserController@status')->name('admin_user_status');
        //账号修改角色
        Route::post('/rolestatus.html', 'Backstage\AdminUserController@roleStatus')->name('admin_user_rolestatus');
    });
//----------------账号管理-------------------------


//----------------节点管理-------------------------
    Route::group(['prefix'=>'AdminNote'], function () {
        Route::get('/index.html', 'Backstage\AdminNoteController@index')->name('admin_note_index');
        //节点编辑创建
        Route::match(array('post','get'),'/save.html', 'Backstage\AdminNoteController@save')->name('admin_note_save');
        //节点删除
        Route::post('/del.html', 'Backstage\AdminNoteController@del')->name('admin_note_del');

    });
//----------------节点管理-------------------------



});


