@extends('admin._layoutNew')
@section('page_head')
@stop

@section('page-content')
    <div class="layui-form">
        <button class="layui-btn layui-btn-normal layui-btn-radius" id="add_admin">新增账号</button>
    </div>

    <table class="layui-hide" id="adminList" lay-filter="adminList"></table>
    <script type="text/html" id="statusTpl">
        <input type="checkbox" name="status" value="@{{d.id}}" lay-skin="switch" lay-text="否|是" lay-filter="status" @{{  d.status == 1 ? 'checked' : '' }} >
    </script>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="edit">修改</a>
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


                $('#add_admin').click(function(){layer_show('新增账号', '/admin/admin_add',800,600);});
                function tbRend(url) {
                    table.render({
                        elem: '#adminList',
                        url: url,
                        page: true,
                        cols: [[
                            {field: 'id', title: 'ID', width: 50},
                            {field: 'title', title: '角色', minWidth: 100},
                            // {field: 'password', title: '密码', minWidth: 100},
                            {field: 'username', title: '姓名', minWidth: 100},
                            {field: 'phone', title: '手机号', minWidth: 150},
                            {field: 'login_num', title: '登录次数', minWidth: 150},
                            {field: 'sort', title: '排序', minWidth: 150},
                            {field: 'create_time', title: '添加时间', minWidth: 150},
                            {field: 'status', title: '是否封号', minWidth: 150, align: 'center', templet: '#statusTpl'},
                            {fixed: 'right', title: '操作', minWidth: 150, align: 'center', toolbar: '#barDemo'}
                        ]]
                    });
                }
                tbRend("{{url('/admin/admin_list')}}");
                $("#search").click(function() {
                    var account = $("input[ name='account']").val();
                    var username = $("input[ name='username']").val();
                    var mobile = $("input[ name='mobile']").val();
                    tbRend("{{url('/admin/admin_list')}}?account=" + account + "&username=" + username + "&mobile=" + mobile);
                    return false;
                });
                form.on('switch(status)', function (obj) {
                    var id = obj.value;
                    $.ajax({
                        url: '{{url('admin/admin_status')}}',
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
                table.on('tool(adminList)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data; //获得当前行数据
                    var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
                    var tr = obj.tr; //获得当前行 tr 的DOM对象

                    if(layEvent === 'edit'){ //编辑
                        //do something
                        layer_show('编辑账号', '/admin/admin_edit?id=' + data.id,800,600);
                    }else if(layEvent === 'del'){ //删除
                        layer.confirm('真的要删除吗？', function(index){
                            //向服务端发送删除指令
                            $.ajax({
                                url:'/admin/admin_del',
                                type:'post',
                                dataType:'json',
                                data:{id:data.id},
                                success:function(res){
                                    if(res.type==='success'){
                                        obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                                        layer.msg(res.message);
                                        layer.close(index);
                                    }else{
                                        layer.close(index);
                                        layer.alert(res.message);
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
