@extends('admin._layoutNew')
@section('page_head')
@stop

@section('page-content')
    <div class="layui-form">
        <button class="layui-btn layui-btn-normal layui-btn-radius" id="add_menu">新增菜单</button>
    </div>
    <table class="layui-hide" id="menuList" lay-filter="menuList"></table>
    <script type="text/html" id="statusTpl">
        <input type="checkbox" name="status" value="@{{d.id}}" lay-skin="switch" lay-text="开|关" lay-filter="status" @{{  d.status == 1 ? 'checked' : '' }} >
    </script>
    <script type="text/html" id="menuStatusTpl">
        <input type="checkbox" name="menu_status" value="@{{d.id}}" lay-skin="switch" lay-text="开|关" lay-filter="menu_status" @{{  d.menu_status == 1 ? 'checked' : '' }} >
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


                $('#add_menu').click(function(){layer_show('新增菜单', '/admin/menu_add',800,600)});
                function tbRend(url) {
                    table.render({
                        elem: '#menuList',
                        url: url,
                        page: false,
                        cols: [[
                            {field: 'id', title: 'ID', width: 100},
                            {field: 'href', title: '链接', minWidth: 100},
                            {field: 'title', title: '名称', minWidth: 100},
                            {field: 'status', title: '状态', minWidth: 150, templet: '#statusTpl'},
                            {field: 'menu_status', title: '是否列表', minWidth: 150, templet: '#menuStatusTpl'},
                            {field: 'sort', title: '排序', minWidth: 150,edit: 'sort'},
                            {fixed: 'right', title: '操作', minWidth: 150, align: 'center', toolbar: '#barDemo'}
                        ]],
                        done: function(res, curr, count) {
                            layer.photos({ //点击图片弹出
                                photos: '.layer-photos-demo'
                                //,anim: 1 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
                            });
                        }
                    });
                }
                //监听单元格编辑
                table.on('edit(menuList)', function(obj){
                    var value = obj.value //得到修改后的值
                        ,data = obj.data //得到所在行所有键值
                        ,field = obj.field; //得到字段
                    $.ajax({
                        url: '{{url('admin/menu_sort')}}',
                        type: 'post',
                        dataType: 'json',
                        data: {id:data.id,sort:value},
                        success: function (res) {
                            layer.msg(res.msg,{icon:1,time:2000},function () {
                                // window.location.reload();
                            });
                        }
                    });
                });
                tbRend("{{url('/admin/menu_list')}}");

                form.on('switch(status)', function (obj) {
                    var id = obj.value;
                    $.ajax({
                        url: '{{url('admin/menu_status')}}',
                        type: 'post',
                        dataType: 'json',
                        data: {id: id},
                        success: function (res) {
                            layer.msg(res.msg,{icon:1,time:2000},function () {
                                window.location.reload();
                            });
                        }
                    });
                });
                form.on('switch(menu_status)', function (obj) {
                    var id = obj.value;
                    $.ajax({
                        url: '{{url('admin/menu_list_status')}}',
                        type: 'post',
                        dataType: 'json',
                        data: {id: id},
                        success: function (res) {
                            layer.msg(res.msg,{icon:1,time:2000},function () {
                                window.location.reload();
                            });
                        }
                    });
                });

                //监听工具条
                table.on('tool(menuList)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data; //获得当前行数据
                    var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
                    var tr = obj.tr; //获得当前行 tr 的DOM对象

                    if(layEvent === 'edit'){ //编辑
                        //do something
                        layer_show('编辑菜单', '/admin/menu_edit?id=' + data.id,800,600);
                    }else if(layEvent === 'del'){ //删除
                        layer.confirm('真的要删除吗？', function(index){
                            //向服务端发送删除指令
                            $.ajax({
                                url:'/admin/menu_del',
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
                    }else if(layEvent === 'add'){ //编辑
                        //do something
                        layer_show('修改轮播图', '/admin/ad_add?id=' + data.id,800,600);
                    }
                });
            });


        }

    </script>

@stop
