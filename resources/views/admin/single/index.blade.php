@extends('admin._layoutNew')
@section('page_head')
@stop

@section('page-content')

    <table class="layui-hide" id="singleList" lay-filter="singleList"></table>
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

                function tbRend(url) {
                    table.render({
                        elem: '#singleList',
                        url: url,
                        page: true,
                        cols: [[
                            {field: 'id', title: 'ID', width: 50},
                            {field: 'title', title: '标题', minWidth: 100},
                            {field: 'create_time', title: '发布时间', minWidth: 150},
                            {fixed: 'right', title: '操作', minWidth: 100, align: 'center', toolbar: '#barDemo'}
                        ]]
                    });
                }
                tbRend("{{url('/admin/single_list')}}");

                //监听工具条
                table.on('tool(singleList)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data; //获得当前行数据
                    var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
                    var tr = obj.tr; //获得当前行 tr 的DOM对象

                    if(layEvent === 'edit'){ //编辑
                        //do something
                        layer_show('修改单页', '/admin/single_add?id=' + data.id,1200,700);
                    }
                });
            });


        }

    </script>

@stop
