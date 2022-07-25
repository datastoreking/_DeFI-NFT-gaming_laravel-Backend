@extends('admin._layoutNew')
@section('page_head')

@stop

@section('page-content')

    <div class="layui-form">
        <button class="layui-btn layui-btn-normal layui-btn-radius" id="add_admin">新增角色</button>
        <div class="layui-inline">
            <label class="layui-form-label">角色名</label>
            <div class="layui-input-inline">
                <input type="text" name="name" placeholder="请输入角色名" autocomplete="off" class="layui-input" value="">
            </div>
        </div>
        <button class="layui-btn btn-search" id="search" lay-submit lay-filter="search"> <i class="layui-icon">&#xe615;</i> </button>
    </div>
    <table class="layui-hide" id="adminUsers" lay-filter="adminList"></table>


    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="permission">修改权限</a>
        <a class="layui-btn layui-btn-xs" lay-event="edit">修改</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
@stop
@section('scripts')
    <script type="text/javascript">
        window.onload = function () {

            layui.use(['layer', 'table'], function () { //独立版的layer无需执行这一句
                var $ = layui.jquery;
                var layer = layui.layer; //独立版的layer无需执行这一句
                var table = layui.table;
                var form = layui.form;
                $('#add_admin').click(function(){layer_show('新增角色', '/admin/role_add');});
                function tbRend(url) {
                    table.render({
                        elem: '#adminUsers',
                        url: url,
                        page: true,
                        cols: [[
                            {field: 'id', title: 'ID', minWidth:50, sort: true},
                            {field: 'name', title: '角色名称',minWidth:150 },
                            {field: 'describe', title: '角色描述',minWidth:150 },
                            {field: 'create_time', title: '添加时间',minWidth:150 },
                            {title: '操作',  minWidth:150,align: 'center', toolbar: '#barDemo'}
                        ]]
                    });
                }
                tbRend("{{url('/admin/role_list')}}");
                $("#search").click(function() {
                    var name = $("input[ name='name']").val();
                    tbRend("{{url('/admin/role_list')}}?name=" + name);
                    return false;
                });
                //监听工具条
                table.on('tool(adminList)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data; //获得当前行数据
                    var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
                    var tr = obj.tr; //获得当前行 tr 的DOM对象

                    if(layEvent === 'del'){ //删除
                        layer.confirm('真的要删除吗？', function(index){

                            //向服务端发送删除指令
                            $.ajax({
                                url:'/admin/role_del',
                                type:'post',
                                dataType:'json',
                                data:{id:data.id},
                                success:function(res){
                                    if(res.type=='success'){
                                        layer.msg(res.msg);
                                        obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                                        layer.close(index);
                                    }else{
                                        layer.close(index);
                                        layer.alert(res.msg);
                                    }
                                }
                            });
                        });
                    } else if(layEvent === 'edit'){ //编辑
                        //do something
                            layer_show('修改管理员', '/admin/role_add?id=' + data.id);
                    } else if(layEvent === 'permission'){
                        layer_show('修改权限','/admin/role_permission?id=' + data.id);
                    }
                });
            });
        }

    </script>

@stop