<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use Illuminate\Support\Facades\Cookie;

class IndexController extends Controller
{
    //注册页面
    public function reg(){
        return view("login.reg");
    }
    //注册方法
    public function regdo(){
        //接值
        $data = request()->except("_token");
        //验证器
        request()->validate([
            'user_name'=>'unique:p_users',
            'user_email'=>'unique:p_users',
            'pwd'=>"regex:/^\w{6,}$/",
            'admin_pwd_confirmation'=>"same:pwd"
        ],[
            'user_name.unique'=>"用户名已存在",
            'user_email.unique'=>'邮箱已存在',
            'pwd.regex'=>'密码必须大于6个字符长度',
            'admin_pwd_confirmation.same'=>"俩次输入密码不符"
        ]);
        //密码加密
        $password = password_hash($data["pwd"],PASSWORD_DEFAULT);
        //入库
        $user = New User;
        $user->user_name = $data["user_name"];
        $user->password = $password;
        $user->user_email = $data["user_email"];
        $user->save();
        if($user){
            //写入注册时间
            $user->reg_time = time();
            $user->save();
            //成功跳转登录页面
            return redirect("/index/login");
        }
    }
    //登录页面
    public function login(){
        return view("login.login");
    }
    //登录方法
    public function logindo(){
        //接值
        $password = request()->password;
        $user_name = request()->user_name;
        //通过用户名查询数据库中是否有此数据
        $userInfo= User::where("user_name",$user_name)->first();
        //查询到后判断密码是否一致
        if($userInfo){
            if(password_verify($password,$userInfo->password)){
                //登录成功更新以下字段：last_login	最后登录时间 last_ip		最后登录的客户端IP
                $userInfo->last_login=time();
                $userInfo->last_ip=request()->getClientIp();
                $userInfo->save();
                //更新成功则登录
                if($userInfo){
                    //存入session
                    //setcookie("uid",$userInfo->user_id,time()+3600,"/");
                    Cookie::queue('uid2',$userInfo->user_name,3600);
                    session(["name"=>$userInfo]);
                    return redirect('/index/user');
                }
            }else{
                //否则提示密码错误
                return redirect("/index/login")->with("msg","密码错误");
            }
        }
    }
    //用户个人中心
    public function user(){
        $v = Cookie::get('uid2');
        echo $v;
        $user_id = session("name.user_id");
        //根据id查询数据
        $userInfo = User::where("user_id",$user_id)->first();
        //数据传输前台
        return view("user/user",["userInfo"=>$userInfo]);
    }
}
