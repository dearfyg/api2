<?php

namespace App\Http\Controllers\Sign;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\sign_user;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
class SignController extends Controller
{
    /****
     *接口加密
    **/
    public function encrypt(){
        //加密数据
        $data = "威慑";
        //设置key
        $key = "1910php";
        //提交方式
        $method = "AES-192-CBC";
        //vi
        $vi = "abcdefghijklmnop";
        //加密方式
        $encrypt = openssl_encrypt($data,$method,$key,OPENSSL_RAW_DATA,$vi);
        //计算签名
        $sign = sha1("施恩".$key);
        //传输的数据
        $data = [
            "data"=>$encrypt,
            "sign"=>$sign
        ];
        $url = "http://api.com/sign/decrypt";
        //请求接口
        $response = $this->curl($url,$data);
        echo $response;
    }
    /***
    请求接口
     **/
    public function curl($url,$data){
        //初始化
        $init = curl_init();
        //设置必要参数
        curl_setopt($init,CURLOPT_URL,$url);//url
        curl_setopt($init,CURLOPT_POST,1);//post传输
        curl_setopt($init,CURLOPT_POSTFIELDS,$data);//post传输的数据
        //执行
        $response = curl_exec($init);
        //错误号和错误信息
        $errno = curl_errno($init);
        $error = curl_error($init);
        //如果有错误号输出错误信息
        if($errno){
            echo $error;die;
        }
        //关闭
        curl_close($init);
        return $response;
    }
    /****
    测试中间件
     **/
    public function decrypt(){
        echo "速度发货时间发货都是JFK";
    }
    /***
    登录
     ***/
    public function login(){
        //账号,密码
        $user_name = request()->user_name;
        $user_pwd = request()->user_pwd;
        //通过user_name去数据库查询
        $info = sign_user::where("user_name",$user_name)->first();
        //如果有账号 判断密码是否一致
        if($info){
            if($user_pwd==$info["user_pwd"]){
                //密码一致判断是否锁定
                if($info["user_lock"]==1){
                    $response = [
                        "code" => "1000",
                        "msg" => "该用户被锁定",
                    ];
                    return $response;
                }else{
                    //生成密钥令牌返回给用户
                    $token = $info["0"].mt_rand(0,9).Str::random(16);
                    //设置
                    Redis::set($info["id"].$token,$token,7200);
                    //返回
                    $response = [
                        "code" => "0",
                        "token" => $token,
                        "expire" => "7200",
                    ];
                    return $response;
                }
            }else{
                $response = [
                    "code" => "50001",
                    "msg" => "请您检查用户账号和密码",
                ];
                return $response;
            }
        }else{
            $response = [
                "code" => "50000",
                "msg" => "请您检查用户账号和密码",
            ];
            return $response;
        }
    }
    /***
     修改用户信息
     **/
    public function user_info(){
        //接token
         $token = request()->token;
         //查询redis是否存在
         $access_token = Redis::keys("*$token");
         //判断是否存在
        if(!$access_token){
            $response = [
                "code" => "60000",
                "msg" => "令牌错误",
            ];
            return $response;
        }else{
            $info = sign_user::where("token",$token)->first();
            if($info["user_lock"]==1){
                $response = [
                    "code" => "70000",
                    "msg" => "该用户已锁定,请联系管理员解锁",
                ];
                return $response;
            }
            return view("sign.userInfo",["info"=>$info]);
        }
    }
}
