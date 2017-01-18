<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
// 搭建后台主页
Route::get('/admin','AdminController@index');

// 用户模块
Route::controller('/admin/user','UserController'); 

// 分类模块
Route::controller('/admin/cate','CateController'); 

// 
Route::controller('home','HomeController');