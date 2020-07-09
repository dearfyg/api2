<?php

namespace App\Http\Controllers\wechat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\wechat_user;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
use App\wechat_media;
use Illuminate\Support\Facades\DB;
class login extends Controller
{
    /***登录页面
     **/
    public function login(){
        return view("wechat.login");
    }
    /***
    登录成功
     **/
    public function logindo(){
        //接手机号
        $name = request()->tel;
        //接法人
        $email = request()->email;
        //通过手机号查询数据库数据
        $info = wechat_user::where("tel",$name)->first();
        //如果有值判断法人是否正却
        if($info){
            if($email==$info["email"]){
                //密码正确登入成功 输出appid和serect
                echo "appid：".$info["appid"]."<br>";
                echo "serect：".$info["serect"];
            }else{
                echo "账号或密码错误";
                header("refresh:1;url=login");
            }
        }else{
            echo "账号或密码错误";
            header("refresh:1;url=login");
        }
    }
    /***
    用户注册表单
     **/
    public function register(){
        return view("wechat.register");
    }
    /***
    注册成功
     ***/
    public function registerdo(){
        //接值
        $data = request()->except("_token");//接值
        //判断是否上传文件
        if (request()->hasFile("image")){
            $data["image"] = $this->upload("image");
        }
        // 计算appid和serect
        $appid = "wx".mt_rand(0,9).Str::random(16);
        $serect = Str::random(32).mt_rand(0,9);
        $data["appid"] = $appid;
        $data["serect"] = $serect;
        //添加入库
        $res = wechat_user::insert($data);
        if($res){
            //注册成功到登录页面
            return redirect("wechat/login");
        }
    }
    /***
    accesstoken
     **/
    public function accesstoken(){
        //接值
        $appid = request()->appid;
        //通过appid查询serect
        $serect_1 = wechat_user::where("appid",$appid)->value("serect");
        if($appid==""){
            $response = [
                "code" => "1",
                "msg" => "appid不可为空",
            ];
            return $response;
        }
        $serect = request()->serect;
        //通过serect查询appid
        $appid_1 = wechat_user::where("serect",$serect)->value("appid");
        if($serect==""){
            $response = [
                "code" => "2",
                "msg" => "serect不可为空",
            ];
            return $response;
        }
        if(!$appid_1){
            $response = [
                "code" => "3",
                "msg" => "serect错误",
            ];
            return $response;
        }
        if(!$serect_1){
            $response = [
                "code" => "4",
                "msg" => "appid错误",
            ];
            return $response;
        }
        //设置accesstoken
        $accesstoken = time().$serect.mt_rand(0,9).Str::random(16);
        //模糊查询redis中是否有该appid的值
        $key = Redis::keys("*$appid*");
        $key = implode($key,",");
        //截取前缀
        $key = substr($key,4);
        //删除查询出来的key
        Redis::del($key);
        //以appid和serect为键存入redis
        Redis::set($appid.$accesstoken,$accesstoken);
        $response = [
            "code" => "0",
            "msg" => "ok",
            "accesstoken" =>$accesstoken,
            "expires_in" => 7200
        ];
        return $response;

    }
    /***
    计划任务
     **/
    public function clear(){
        //获取前缀为accoss_total的所有键值
       $key =  Redis::keys("accoss_total*");
       //如果有则循环删除
        if($key){
            foreach($key as $k=>$v){
                $a = substr($v,4);
                Redis::del($a);
            }
        }

    }
    /***
    素材接口
     **/
    public function source(){
        //素材类型
        $type = request()->type;
        //素材media
        $media = request()->media;
        if(!$type){
            $response = [
                'code'=>"50001",
                'msg'=>"素材类型不可为空"
            ];
            return $response;
        }
        if(!$media){
            $response = [
                'code'=>"50002",
                'msg'=>"素材media不可为空"
            ];
            return $response;
        }
        if($type!="image" && $type!="voice" && $type!="video" && $type!="thumb"){
            $response = [
                'code'=>"50003",
                'msg'=>"素材类型识别错误"
            ];
            return $response;
        }
        //文件上传
        //判断是否有文件上传
        if (request()->hasFile("media")){
            $media = $this->upload("media");
        }
        $url = env("IMG_URL").storage_path("app/".$media);
        $a_media = sha1(time().mt_rand(0,9));
        $data = [
            "image_url"=>$url,
            "media"=>$a_media,
            "image"=>$media,
            "time"=>time()
        ];
        //上传数据库
        $res = wechat_media::insert($data);
        //如果成功返回值
        if($res){
            $response = [
                "url"=>$url,
                "media"=>$a_media

            ];
            return $response;
        }
    }
    /***
    素材定时删除接口
     **/
    public function sourcedel(){
        $info = wechat_media::all();
        foreach($info as $k=>$v){
           if(time()-$v["time"]>=600){
               wechat_media::destroy("id",$v["id"]);
               $img = DB::table('wechat_media')->where('id',$v["id"])->value('image');
               if($img){
                   unlink(storage_path('app/'.$img));
               }
           }
        }
    }
    /**
    素材删除
     ***/
    public function del(){
        //接media
        $media = request()->media;
        if(!$media) {
            $response = [
                "code" => "50005",
                "msg" => "缺少media参数"
            ];
            return $response;
        }
        $res = wechat_media::where("media",$media)->first();
        if(!$res){
            $response = [
                "code" => "50006",
                "msg" => "media错误"
            ];
            return $response;
        }
        $res = wechat_media::where("media",$media)->delete();//删除数据库
        $img = DB::table('wechat_media')->where('media',$media)->value('image');//查询本地路径
        if($img){
            unlink(storage_path('app/'.$img));//物理删除
        }
        $response = [
            "code" => "0",
            "msg" => "ok"
        ];
        return $response;

    }
    /***文件上传
     **/
    public function upload($img){
        //判断上传文件是否有错误
        if (request()->file($img)->isValid()) {
            //接图片
            $photo = request()->$img;
            //指定路径
            $path = $photo->store('upload');
            //返回路径
            return $path;
        }
        exit('未获取到上传文件或上传过程出错');
    }
}
