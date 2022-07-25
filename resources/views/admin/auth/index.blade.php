@extends('admin._layoutNew')
@section('page_head')
@stop

@section('page-content')
    <div class="layui-form">
        <button class="layui-btn layui-btn-normal layui-btn-radius" id="add_auth">新增角色</button>
    </div>

    <table class="layui-hide" id="authList" lay-filter="authList"></table>
    <script type="text/html" id="statusTpl">
        <input type="checkbox" name="status" value="@{{d.id}}" lay-skin="switch" lay-text="开|关" lay-filter="status" @{{  d.status == 2 ? 'checked' : '' }} >
    </script>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="edit">修改</a>
        <a class="layui-btn layui-btn-xs" lay-event="auth">授权</a>
    </script>

    <div class="ibox-content" style="display: none" id="role">
        <input type="hidden" id="uid">
        <div class="layui-input-block">
            <h3 id="name"></h3>
        </div>
        <div class="form-group">
            <div id="node" class="demo-tree"></div>
        </div>
        <button class="layui-btn layui-btn-sm" style="margin-left: 30%" id="postform">确认分配</button>
    </div>
@stop
@section('scripts')
    <script type="text/javascript">
        window.onload = function () {
            layui.use(['layer', 'table','form','laydate'], function () { //独立版的layer无需执行这一句
                var $ = layui.jquery;
                var layer = layui.layer; //独立版的layer无需执行这一句
                var table = layui.table;
                var form = layui.form;


                $('#add_auth').click(function(){layer_show('新增角色', '/admin/auth_add',800,600);});
                function tbRend(url) {
                    table.render({
                        elem: '#authList',
                        url: url,
                        page: true,
                        cols: [[
                            {field: 'id', title: 'ID', width: 50},
                            {field: 'title', title: '权限名称', minWidth: 100},
                            {field: 'remark', title: '备注信息', minWidth: 100},
                            {field: 'create_time', title: '添加时间', minWidth: 150},
                            {field: 'status', title: '状态', minWidth: 150, align: 'center', templet: '#statusTpl'},
                            {fixed: 'right', title: '操作', minWidth: 150, align: 'center', toolbar: '#barDemo'}
                        ]]
                    });
                }
                tbRend("{{url('/admin/auth_list')}}");
                form.on('switch(status)', function (obj) {
                    var id = obj.value;
                    $.ajax({
                        url: '{{url('admin/auth_state')}}',
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
                table.on('tool(authList)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data; //获得当前行数据
                    var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
                    var tr = obj.tr; //获得当前行 tr 的DOM对象

                    if(layEvent === 'edit'){ //编辑
                        //do something
                        layer_show('修改角色', '/admin/auth_edit?id=' + data.id,800,600);
                    }else if(layEvent === 'del'){ //删除
                        layer.confirm('真的要删除吗？', function(index){
                            //向服务端发送删除指令
                            $.ajax({
                                url:'/admin/auth_del',
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
                    }else if(layEvent === 'auth'){ //编辑
                        //do something
                        layer_show('权限授权', '/admin/auth_node?id=' + data.id,800,600);
                        // function auth(){
                            //加载层
                            {{--index2 = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2--}}
                            {{--//获取权限信息--}}
                            {{--$.get("{{url('admin/auth_node')}}?id="+ data.id,function(res){--}}

                            {{--    console.log(res)--}}
                            {{--    return false;--}}

                            {{--    layer.close(index2);--}}
                            {{--    //页面层--}}
                            {{--    tree.render({--}}
                            {{--        elem: '#node'--}}
                            {{--        ,data: res.data--}}
                            {{--        ,showCheckbox: true--}}
                            {{--        ,id: 'id'--}}
                            {{--    });--}}
                            {{--    index = layer.open({--}}
                            {{--        category: 1,--}}
                            {{--        area:['50%', '80%'],--}}
                            {{--        title:'权限分配',--}}
                            {{--        skin: 'layui-layer-demo', //加上边框--}}
                            {{--        content: $('#role')--}}
                            {{--    });--}}


                            {{--});--}}
                        // }
                    }
                });
            });


        }

    </script>

@stop
