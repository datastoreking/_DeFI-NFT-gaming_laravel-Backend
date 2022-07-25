<!doctype html>
<html  class="x-admin-sm">
<head>
	<meta charset="UTF-8">
	<title>后台登录</title>
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="{{URL("css/login.css")}}">
	<link rel="stylesheet" href="{{URL("layui/css/layui.css")}}">
    <link rel="stylesheet" href="{{URL("css/admin.css")}}">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="{{URL("layui/layui.js")}}" charset="utf-8"></script>
    <!--[if lt IE 9]>
      <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
      <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="login-bg" style="background:url({{\App\Models\Config::getValue('site_bg')}}) no-repeat center;
    background-size: cover;
    overflow: hidden;">

    <div class="login layui-anim layui-anim-up">
        <div class="message">管理后台登录</div>
        <div id="darkbannerwrap"></div>

        <form method="post" class="layui-form" >
            <input name="account" placeholder="用户名"  type="text" lay-verify="required" class="layui-input" >
            <hr class="hr15">
            <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input">
            <hr class="hr15">
            <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
            <hr class="hr20" >
        </form>
    </div>

    <script>
        $(function  () {
            layui.use('form', function(){
              var form = layui.form;
              //监听提交
              form.on('submit(login)', function(data){
                  $.ajax({
                      url:'/admin/login',
                      type:'post',
                      dataType:'json',
                      data:data.field,
                      success:function(res){
                          if(res.type === 'success'){
                              layer.msg(res.msg);
                              window.location="/admin/index";
                          }else{
                              layer.msg(res.msg,{icon:2,time:2000},function () {
                                  var index = parent.layer.getFrameIndex(window.name);
                                  parent.layer.close(index);
                              });
                          }

                      }
                  });
                  return false;
              });
            });
        })
    </script>
    <!-- 底部结束 -->
</body>
</html>
