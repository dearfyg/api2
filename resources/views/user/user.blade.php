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
<table class="table table-bordered">
    <h2>欢迎，{{Cookie::get("uid2")}}再次回来</h2>
    <caption>用户个人信息</caption>
    <thead>
    <tr>
        <th>用户名</th>
        <th>Email</th>
        <th>注册时间</th>
        <th>最后一次登录时间</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>{{$userInfo->user_name}}</td>
        <td>{{$userInfo->user_email}}</td>
        <td>{{$userInfo->reg_time}}</td>
        <td>{{$userInfo->last_login}}</td>









    















    </tbody>
</table>
</center>
</body>
</html>