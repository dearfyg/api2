<?php

namespace App\Http\Middleware;

use Closure;

class Sign
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
        //接值
        $sign = $request->sign;
        //相同的key
        $key = "1910php";
        //加密
        $sign_a = sha1("施恩".$key);
        //判断签名是否一致
        if($sign_a!=$sign){
            $response = [
                "code" => "40004",
                "msg" =>"签名错误"
            ];
            echo json_encode($response,JSON_UNESCAPED_UNICODE);die;
        }
        return $next($request);
//
    }
}
