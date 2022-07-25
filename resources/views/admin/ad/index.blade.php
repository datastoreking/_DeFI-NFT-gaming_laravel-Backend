@extends('admin._layoutNew')
@section('page_head')
@stop

@section('page-content')
    <div class="layui-form">
        <button class="layui-btn layui-btn-normal layui-btn-radius" id="add_ad">新增轮播图</button>
    </div>

    <table class="layui-hide" id="adList" lay-filter="adList"></table>
    <script type="text/html" id="typeTpl">
        @{{ d.type == 1 ? '<span class="layui-badge layui-bg-green">首页轮播</span>' : '' }}
        @{{ d.type == 2 ? '<span class="layui-badge layui-bg-blue">个人中心</span>' : '' }}
    </script>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="edit">修改</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
@stop
@section('scripts')
    <script type="text/javascript">
        window.onload = function () {
            layui.use(['layer', 'table','form','laydate'], function () { //独立版的layer无需执行这一句
                var $ = layui.jquery;
                var layer = layui.layer; //独立版的layer无需执行这一句
                var table = layui.table;
                var form = layui.form;


                $('#add_ad').click(function(){layer_show('新增轮播图', '/admin/ad_add',800,600);});
                function tbRend(url) {
                    table.render({
                        elem: '#adList',
                        url: url,
                        page: true,
                        cols: [[
                            {field: 'id', title: 'ID', width: 50},
                            {field: 'image', title: '图片', minWidth: 150, templet: function(d) {
                                    var html = '<div class="layer-photos-demo" onclick="img_click()" style="cursor:pointer;" >';
                                    html += ' <img layer-pid=""  layer-src="' + d.image + '" src="' + d.image + '" style="width: 80px;height: 38px;">';
                                    html += '</div>';
                                    return html
                                }
                            },
                            {field: 'url', title: '跳转链接', minWidth: 150},
                            {field: 'type', title: '类型', minWidth: 100, align: 'center', templet: '#typeTpl'},
                            {field: 'create_time', title: '发布时间', minWidth: 150},
                            {field: 'right', title: '操作', minWidth: 150, align: 'center', toolbar: '#barDemo'}
                        ]],
                        done: function(res, curr, count) {
                            layer.photos({ //点击图片弹出
                                photos: '.layer-photos-demo'
                                //,anim: 1 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
                            });
                        }
                    });
                }
                tbRend("{{url('/admin/ad_list')}}");

                //监听工具条
                table.on('tool(adList)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data; //获得当前行数据
                    var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
                    var tr = obj.tr; //获得当前行 tr 的DOM对象

                    if(layEvent === 'edit'){ //编辑
                        //do something
                        layer_show('修改轮播图', '/admin/ad_add?id=' + data.id,800,600);
                    }else if(layEvent === 'del'){ //删除
                        layer.confirm('真的要删除吗？', function(index){
                            //向服务端发送删除指令
                            $.ajax({
                                url:'/admin/ad_del',
                                type:'post',
                                dataType:'json',
                                data:{id:data.id},
                                success:function(res){
                                    if(res.type==='success'){
                                        obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                                        layer.msg(res.msg);
                                        layer.close(index);
                                    }else{
                                        layer.close(index);
                                        layer.alert(res.msg);
                                    }
                                }
                            });
                        });
                    }
                });
            });


        }

    </script>

@stop
