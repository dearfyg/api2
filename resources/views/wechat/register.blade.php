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
    <form class="form-horizontal" role="form" action="{{url("wechat/registerdo")}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="firstname" class="col-sm-2 control-label">公司名</label>
            <div class="col-sm-10">
                <input type="text" class="form-control"  name="company" placeholder="请输入公司名">
            </div>
        </div>
        <div class="form-group">
            <label for="firstname" class="col-sm-2 control-label">法人</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="legal"  placeholder="请输入法人">
            </div>
        </div>
        <div class="form-group">
            <label for="lastname" class="col-sm-2 control-label">公司地址</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="address"  placeholder="请输入公司地址">
            </div>
        </div>
        <div class="form-group">
            <label for="lastname" class="col-sm-2 control-label">营业执照照片</label>
            <div class="col-sm-10">
                <input type="file" name="image">
            </div>
        </div>
            <label for="lastname" class="col-sm-2 control-label">联系人电话</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="tel" placeholder="请输入联系人电话">
            </div>
            <label for="lastname" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="email"  placeholder="请输入Email">
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
