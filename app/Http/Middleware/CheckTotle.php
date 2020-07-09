<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
class CheckTotle
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
        //获取当前网页的uri
        $uri = $_SERVER['REQUEST_URI'];
        //转为哈希键
        $key = substr((md5($uri)),5,10);
        //拼接键值
        $key = "accoss_total".$key;
        //redis查询该键的值
        $total = Redis::get($key);
        //如果大于5让他过十秒再试
        if($total >=100){
            $response = [
                'code'=>'50009',
                'msg'=>"今天请求的接口已到上限"
            ];
            echo json_encode($response,JSON_UNESCAPED_UNICODE);
            die;
        }else{
            //自增
            $num = Redis::incr($key);
            $response = [
                'code'=>'0',
                'msg'=>"ok,目前请求第 $num 次"
            ];
            //过了1分清空key 重新计数
//            Redis::expire($key,60);
            echo json_encode($response,JSON_UNESCAPED_UNICODE);
        }
        return $next($request);
    }
}
