<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//$api = app('Dingo\Api\Routing\Router');
//$api->version('v1', function ($api) {
//    $api->group(['middleware' => ['api','cors'],'namespace' => 'App\Http\Controllers\Api\v1',], function ($api) {
//        // login
//        $api->get('authorization', [
//            'as' => 'auth.login',
//            'uses' => 'LoginController@login',
//        ]);
//        // login
//        $api->post('register', [
//            'as' => 'auth.register',
//            'uses' => 'LoginController@register',
//        ]);
//
//        $api->group(['middleware' => ['jwt.auth']], function ($api) {
//
//            // login
//            $api->post('index', [
//                'as' => 'auth.index',
//                'uses' => 'LoginController@index',
//            ]);
//            // login
//            $api->post('refresh', [
//                'as' => 'auth.index',
//                'uses' => 'LoginController@refreshToken',
//            ]);
//        });
//    });
//});
