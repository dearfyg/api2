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
    <table>
        <tr>
            <td>账号</td>
            <td>密码</td>
            <td>是否锁定</td>
            <td>是否管理员</td>
            <td>操作</td>
        </tr>
        <tr>
            <td>{{$info->user_name}}</td>
            <td>{{$info->user_pwd}}</td>
            <td>@if($info->user_lock==1) 锁定 @else 未锁定 @endif</td>
            <td>@if($info->is_vip==0) 否 @else 是 @endif</td>
            <td>@if($info->is_vip==1) <a href="{{url("/")}}?id={{$info->id}}">锁定</a> <a href="">修改</a> @else 修改 @endif</td>
        </tr>
    </table>
</center>
</body>
</html>