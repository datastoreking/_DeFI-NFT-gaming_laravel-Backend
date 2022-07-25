@extends('admin._layoutNew')
@section('page_head')
@stop

@section('page-content')
    <div class="layui-form">
        <button class="layui-btn layui-btn-normal layui-btn-radius" id="add_phone">添加概率</button>
    </div>
    <input type="hidden" id="box_id" value="{{$box_id}}">
    <table class="layui-hide" id="phoneList" lay-filter="phoneList"></table>
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
                var box_id = $("#box_id").val();
                $('#add_phone').click(function(){layer_show('添加概率', '/admin/box_level_add?box_id='+box_id,800,600)});
                function tbRend(url) {
                    table.render({
                        elem: '#phoneList',
                        url: url,
                        page: true,
                        cols: [[
                            {field: 'id', title: 'ID', width: 100},
                            {field: 'level', title: '等级', minWidth: 100},
                            {field: 'ratio', title: '概率', minWidth: 100},
                            {field: 'create_time', title: '添加时间', minWidth: 150},
                            {fixed: 'right', title: '操作', minWidth: 150, align: 'center', toolbar: '#barDemo'}
                        ]]
                    });
                }
                tbRend("{{url('/admin/box_level_list')}}?box_id="+box_id);
                //监听工具条
                table.on('tool(phoneList)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data; //获得当前行数据
                    var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
                    var tr = obj.tr; //获得当前行 tr 的DOM对象
                    if(layEvent === 'edit'){ //编辑
                        //do something
                        layer_show('编辑概率', '/admin/box_level_add?id=' + data.id+'&box_id='+box_id,800,500);
                    }else if(layEvent === 'del'){ //删除
                        layer.confirm('真的要删除吗？', function(index){
                            //向服务端发送删除指令
                            $.ajax({
                                url:'/admin/box_level_del',
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
