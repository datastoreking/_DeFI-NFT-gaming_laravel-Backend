<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>后台管理</title>
  <link rel="stylesheet" href="{{url('layui/css/layui.css')}}" media="all">
  <link rel="stylesheet" href="{{url("layui/css/app.css")}}" media="all" />
  <link rel="stylesheet" href="{{url("css/default.css")}}" media="all" id="skin" kit-skin />
  <link rel="stylesheet" href="{{url("css/iconfont.css")}}" type="text/css">
  <link rel="stylesheet" href="{{url('formSelects/dist/formSelects-v4.css')}}">
</head>

<body class="kit-theme">
    <div class="layui-layout layui-layout-admin kit-layout-admin">
        <div class="layui-header">
            <div class="layui-logo">后台管理</div>
            <ul class="layui-nav layui-layout-right kit-nav">
                <li class="layui-nav-item">
                <a href="javascript:;"><i class="layui-icon">&#xe63f;</i> 皮肤</a>
                <dl class="layui-nav-child skin">
                    <dd><a href="javascript:;" data-skin="default" style="color:#393D49;"><i class="layui-icon">&#xe658;</i> 默认</a></dd>
                    <dd><a href="javascript:;" data-skin="orange" style="color:#ff6700;"><i class="layui-icon">&#xe658;</i> 橘子橙</a></dd>
                    <dd><a href="javascript:;" data-skin="green" style="color:#00a65a;"><i class="layui-icon">&#xe658;</i> 原谅绿</a></dd>
                    <dd><a href="javascript:;" data-skin="pink" style="color:#FA6086;"><i class="layui-icon">&#xe658;</i> 少女粉</a></dd>
                    <dd><a href="javascript:;" data-skin="blue.1" style="color:#00c0ef;"><i class="layui-icon">&#xe658;</i> 天空蓝</a></dd>
                    <dd><a href="javascript:;" data-skin="red" style="color:#dd4b39;"><i class="layui-icon">&#xe658;</i> 枫叶红</a></dd>
                </dl>
                </li>
                <li class="layui-nav-item"> {{\Illuminate\Support\Facades\Session::get('admin.username')}}</li>
                <li class="layui-nav-item"><a href="{{url('admin/logout')}}"><i class="fa fa-sign-out" aria-hidden="true"></i> 注销</a></li>
            </ul>
        </div>

        <div class="layui-side layui-bg-black kit-side">
            <div class="layui-side-scroll">
                <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
                <ul class="layui-nav layui-nav-tree" lay-filter="kitNavbar" kit-navbar>
                    @foreach($list as $item)
                    <li class="layui-nav-item">
                    <a href="javascript:;"><span class="iconfont icon-{{$item['icon']}}"></span><span> {{$item['title']}}</span></a>
                        @foreach($item['second'] as $value)
                        <dl class="layui-nav-child">
                            <dd><a href="javascript:;" kit-target data-options="{url:'{{'/'.$value["href"]}}',title:'{{$value["title"]}}',id:'{{$value["id"]}}'}"><span class="iconfont icon-{{$value['icon']}}"></span><span> {{$value['title']}}</span></a></dd>
                        </dl>
                        @endforeach
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="layui-body" id="container">
            <!-- 内容主体区域 -->
            <div style="padding: 15px;"><i class="layui-icon layui-anim layui-anim-rotate layui-anim-loop">&#xe63e;</i> 请稍等...</div>
        </div>
    </div>

    <script src="{{url('layui/layui.js')}}"></script>
    <script src="{{url('js/tableSelect.js')}}"></script>
    <script src="{{url('js/jquery-3.5.1.min.js')}}"></script>

    <script>
        var message;
        layui.config({
            base: '/js/src/js/',
            version: '1.0.1'
        }).use(['app', 'message'], function() {
            var app = layui.app,
            $ = layui.jquery,
            layer = layui.layer;
            //将message设置为全局以便子页面调用
            message = layui.message;
            //主入口
            app.set({type: 'iframe'}).init();
            $('dl.skin > dd').on('click', function() {
                var $that = $(this);
                var skin = $that.children('a').data('skin');
                switchSkin(skin);
            });
            var setSkin = function(value) {
                layui.data('kit_skin', {
                key: 'skin',
                value: value
              });
            },
            getSkinName = function() {
                return layui.data('kit_skin').skin;
            },
            switchSkin = function(value) {
                var _target = $('link[kit-skin]')[0];
                _target.href = _target.href.substring(0, _target.href.lastIndexOf('/') + 1) + value + _target.href.substring(_target.href.lastIndexOf('.'));
                setSkin(value);
            },
            initSkin = function() {
                var skin = getSkinName();
                switchSkin(skin === undefined ? 'default' : skin);
            }();
        });
    </script>
</body>

</html>
