<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<center>
    <h1>用户注册</h1>
    <form class="form-horizontal" role="form" action="{{url("index/regdo")}}" method="post">
        @csrf
        <div class="form-group">
            <label for="firstname" class="col-sm-2 control-label">用户名</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="firstname" name="user_name" placeholder="请输入用户名">

            </div>
            <span><font color="red">{{$errors->first("user_name")}}</font></span>
        </div>
        <div class="form-group">
            <label for="firstname" class="col-sm-2 control-label">邮箱</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="user_email" id="firstname" placeholder="请输入邮箱">

            </div>
            <span><font color="red">{{$errors->first("user_email")}}</font></span>
        </div>
        <div class="form-group">
            <label for="lastname" class="col-sm-2 control-label">密码</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="pwd" id="lastname" placeholder="请输入密码">

            </div>
            <span><font color="red">{{$errors->first("pwd")}}</font></span>
        </div>
        <div class="form-group">
            <label for="lastname" class="col-sm-2 control-label">确认密码</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" name="admin_pwd_confirmation" id="lastname" placeholder="请确认密码">

            </div>
            <span><font color="red">{{$errors->first("admin_pwd_confirmation")}}</font></span>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">注册</button>
            </div>
        </div>
    </form>
</center>
</body>
</html>
