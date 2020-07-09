<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Token;
use Illuminate\Support\Facades\Redis;
class UserController extends Controller
{
    //验证签名
    public function serect(){
        //key值
        $key = "12262115";
        //接收数据
        $data = request()->data;
        //签名
        $sign = request()->sign;
        //目前的签名
        $now_sign = sha1($data.$key);
        //判断签名是否一致
        if($sign==$now_sign){
            echo "验签通过";
        }else{
            echo "验签失败";
        }
    }
    //对称加密
    public function encrypt(){
        echo __METHOD__;
    }
    //非对称加密
    public function encrypt1(){
        //接值
        $data = $_POST["data"];
        //base64解密
        $base_data = base64_decode($data);
        //获取私钥
        $priv_content = file_get_contents(storage_path("keys/b_priv.key"));
        //私钥证书
        $priv = openssl_get_privatekey($priv_content);
        //解密
        openssl_private_decrypt($base_data,$de_data,$priv);
//        echo "口令：".$de_data."<hr>";

        //回令
        $data2 = "宝塔镇河妖";
        //获取公钥
        $pub_content = file_get_contents(storage_path("keys/pub.key"));
        //公钥证书
        $pubkey = openssl_get_publickey($pub_content);
        //加密
        openssl_public_encrypt($data2,$en_data2,$pubkey);
        //base64转码
        $data2 = base64_encode($en_data2);
        //返回值
        $response = [
            'code'=>0,
            "msg"=>'ok',
            'data'=>$data2
        ];
        return $response;
    }
}
