<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get("/goods","Goods\GoodsController@details");
Route::get("/test","Goods\GoodsController@redis");

Route::get("/index/reg","Login\IndexController@reg");//注册
Route::any("/index/regdo","Login\IndexController@regdo");//注册方法

Route::get("/index/login","Login\IndexController@login");//登录
Route::any("/index/logindo","Login\IndexController@logindo");//登录方法

Route::get("/index/user","Login\IndexController@user");//用户个人中心



Route::get("serect","Api\UserController@serect");//验签
Route::post("encrypt","Api\UserController@encrypt");//对称加密
Route::post("encrypt1","Api\UserController@encrypt1");//非对称加密



Route::prefix("wechat")->group(function(){
    Route::get("login","wechat\login@login");//登录表单
    Route::get("register","wechat\login@register");//注册表单
    Route::post("registerdo","wechat\login@registerdo");//注册成功
    Route::post("logindo","wechat\login@logindo");//登录成功
    Route::get("accesstoken","wechat\login@accesstoken")->middleware("total");//accesstoken接口
    Route::get("clear","wechat\login@clear");//每日0.00清空获取次数
    Route::post("source","wechat\login@source")->middleware("login");//素材上传接口
    Route::post("sourcedel","wechat\login@sourcedel")->middleware("login");//素材定时删除接口
    Route::post("del","wechat\login@del")->middleware("login");//素材删除接口
});








Route::prefix("sign")->group(function(){
    Route::get("encrypt","Sign\SignController@encrypt");//密文传输
    Route::post("decrypt","Sign\SignController@decrypt")->middleware("sign");//密文传输
    Route::post("login","Sign\SignController@login");//登录
    Route::get("user","Sign\SignController@user_info");//修改信息

});

