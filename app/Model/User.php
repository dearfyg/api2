<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = "p_users";
    protected $primaryKey = "user_id";
    public $timestamps = false;
    protected $dates = ['reg_time',"last_login"];
}
