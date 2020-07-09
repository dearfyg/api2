<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class wechat_user extends Model
{
    protected $table = "wechat_user";
    protected $primaryKey = "id";
    public $timestamps = false;
}
