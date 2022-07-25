@extends('admin._layoutNew')
@section('page_head')
@stop

@section('page-content')
    <div class="layui-form">
        <button class="layui-btn layui-btn-normal layui-btn-radius" id="add_type_one">添加无限赏</button>
        <div class="layui-inline">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-inline">
                <input type="text" name="name" placeholder="请输入名称" autocomplete="off" class="layui-input" value="">
            </div>
        </div>
        <button class="layui-btn btn-search" id="search" lay-submit lay-filter="mobile_search"> <i class="layui-icon">&#xe615;</i> </button>
    </div>
    <table class="layui-hide" id="phoneList" lay-filter="phoneList"></table>
    <script type="text/html" id="stateTpl">
        <input type="checkbox" name="state" value="@{{d.id}}" lay-skin="switch" lay-text="开|关" lay-filter="state" @{{  d.state == 2 ? 'checked' : '' }}>
    </script>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="level">级别</a>
        <a class="layui-btn layui-bg-blue layui-btn-xs" lay-event="edit">修改</a>
        <a class="layui-btn layui-bg-orange layui-btn-xs" lay-event="prize">发赏</a>
        <a class="layui-btn layui-bg-red layui-btn-xs" lay-event="del">删除</a>
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
                $('#add_type_one').click(function(){
                    layer.open({
                        type: 2,
                        area: ['100%', '100%'],
                        fixed: false, //不固定
                        maxmin: true,
                        title:'添加无限赏',
                        content: '/admin/box_three_add'
                    });
                });
                function tbRend(url) {
                    table.render({
                        elem: '#phoneList',
                        url: url,
                        page: true,
                        cols: [[
                            {field: 'id', title: 'ID', width: 100},
                            {field: 'category_name', title: '分类', width: 100},
                            {field: 'name', title: '名称', minWidth: 100},
                            {field: 'image', title: '图片', minWidth: 150, templet: function(d) {
                                    var html = '<div class="layer-photos-demo" onclick="img_click()" style="cursor:pointer;" >';
                                    html += ' <img layer-pid=""  layer-src="' + d.image + '" src="' + d.image + '" style="width: 80px;height: 38px;">';
                                    html += '</div>';
                                    return html
                                }
                            },
                            {field: 'cover_image', title: '封面图', minWidth: 150, templet: function(d) {
                                    var html = '<div class="layer-photos-demo" onclick="img_click()" style="cursor:pointer;" >';
                                    html += ' <img layer-pid=""  layer-src="' + d.cover_image + '" src="' + d.cover_image + '" style="width: 80px;height: 38px;">';
                                    html += '</div>';
                                    return html
                                }
                            },
                            {field: 'price', title: '价格', minWidth: 100},
                            {field: 'state', title: '是否下架', minWidth: 100, align: 'center', templet: '#stateTpl'},
                            {field: 'sort', title: '排序值', minWidth: 100},
                            {field: 'create_time', title: '添加时间', minWidth: 150},
                            {field: 'right', title: '操作', minWidth: 200, align: 'center', toolbar: '#barDemo'}
                        ]],
                        done: function(res, curr, count) {
                            layer.photos({ //点击图片弹出
                                photos: '.layer-photos-demo'
                                //,anim: 1 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
                            });
                        }
                    });
                }
                tbRend("{{url('/admin/box_three_list')}}");
                $("#search").click(function() {
                    var name = $("input[ name='name']").val();
                    tbRend("{{url('/admin/box_three_list')}}?name="+ name);
                    return false;
                });
                form.on('switch(state)', function (obj) {
                    var id = obj.value;
                    $.ajax({
                        url: '{{url('admin/box_three_state')}}',
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
                table.on('tool(phoneList)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data; //获得当前行数据
                    var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
                    var tr = obj.tr; //获得当前行 tr 的DOM对象
                    if(layEvent === 'level'){ //编辑
                        layer.open({
                            type: 2,
                            area: ['100%', '100%'],
                            fixed: false, //不固定
                            maxmin: true,
                            title: '无限赏概率列表',
                            content: '/admin/box_level_index?box_id='+data.id
                        });
                    }else if(layEvent === 'edit'){ //编辑
                        //do something
                        layer.open({
                            type: 2,
                            area: ['100%', '100%'],
                            fixed: false, //不固定
                            maxmin: true,
                            title:'编辑无限赏',
                            content: '/admin/box_three_add?id=' + data.id
                        });
                    }else if(layEvent === 'prize'){ //编辑
                        //do something
                        layer.open({
                            type: 2,
                            area: ['600px', '600px'],
                            fixed: false, //不固定
                            maxmin: true,
                            title:'无限赏发赏',
                            content: '/admin/box_three_prize?box_id=' + data.id
                        });
                    }else if(layEvent === 'del'){ //删除
                        layer.confirm('真的要删除吗？', function(index){
                            //向服务端发送删除指令
                            $.ajax({
                                url:'/admin/box_three_del',
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
