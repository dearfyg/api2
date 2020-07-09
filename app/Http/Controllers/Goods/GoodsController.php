<?php

namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Goods;
use DB;
use Illuminate\Support\Facades\Redis;//使用redis
class GoodsController extends Controller
{
    //
    public function details(){
        //获取商品id
        $goods_id = request()->id;
        echo "goods_id:".$goods_id;
        $info = Goods::find($goods_id);
        $info = DB::table('p_goods')->where(['goods_id'=>$goods_id])->first();
       dd($info);
    }
    public function redis(){
        $key = "name1";
        $val = redis::get($key);
        echo "val:".$val;
    }
}
