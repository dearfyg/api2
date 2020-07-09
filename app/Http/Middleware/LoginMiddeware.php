<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
class LoginMiddeware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        //获取session
//        $user = session("name");
//        if(!$user){
//            //没有则提示登录。让其到登录页面
//            return redirect('/index/login')->with("msg","请您先登录");
//        }
        //获取token
        $token = request()->input("token");
        //用token做键名查询redis里面是否有此键
        $uid = Redis::keys("*$token");
        //如果没有uid则证明没有token
        if(!$uid){
            $response = [
                "code"=>"50008",
                "msg"=>"鉴权失败"
            ];
            echo json_encode($response,JSON_UNESCAPED_UNICODE);die;
        }
        return $next($request);
    }
}
