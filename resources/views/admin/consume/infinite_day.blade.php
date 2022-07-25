@extends('admin._layoutNew')
@section('page_head')
@stop

@section('page-content')
    <div class="layui-form">
        <div class="layui-inline">
            <label class="layui-form-label">日期</label>
            <div class="layui-input-inline">
                <input type="text" name="day" id="day" placeholder="请选择日期" autocomplete="off" class="layui-input" value="" >
            </div>
        </div>
        <button class="layui-btn btn-search" id="search" lay-submit lay-filter="mobile_search"> <i class="layui-icon">&#xe615;</i> </button>
    </div>
    <table class="layui-hide" id="adList" lay-filter="adList"></table>
@stop
@section('scripts')
    <script type="text/javascript">
        window.onload = function () {
            layui.use(['layer', 'table','form','laydate'], function () { //独立版的layer无需执行这一句
                var $ = layui.jquery;
                var layer = layui.layer; //独立版的layer无需执行这一句
                var table = layui.table;
                var form = layui.form;
                var laydate = layui.laydate;
                laydate.render({
                    elem: '#day',
                    type: 'date'
                });
                function tbRend(url) {
                    table.render({
                        elem: '#adList',
                        url: url,
                        page: true,
                        cols: [[
                            {field: 'uid', title: '用户ID', minWidth: 100},
                            {field: 'rank', title: '名次', minWidth: 100},
                            {field: 'nickname', title: '昵称', minWidth: 150},
                            {field: 'avatar', title: '头像', minWidth: 150, templet: function(d) {
                                    var html = '<div class="layer-photos-demo" onclick="img_click()" style="cursor:pointer;" >';
                                    html += ' <img layer-pid=""  layer-src="' + d.avatar + '" src="' + d.avatar + '" style="width: 80px;height: 38px;">';
                                    html += '</div>';
                                    return html
                                }
                            },
                            {field: 'achieve', title: '消费金额', minWidth: 150},
                            {field: 'create_time', title: '日期', minWidth: 150},
                        ]],
                        done: function(res, curr, count) {
                            layer.photos({ //点击图片弹出
                                photos: '.layer-photos-demo'
                                //,anim: 1 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
                            });
                        }
                    });
                }
                tbRend("{{url('/admin/infinite_day_list')}}");
                $("#search").click(function() {
                    var day = $("#day").val();
                    tbRend("{{url('/admin/infinite_day_list')}}?day=" + day);
                    return false;
                });
                //监听工具条
                table.on('tool(adList)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data; //获得当前行数据
                    var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
                    var tr = obj.tr; //获得当前行 tr 的DOM对象


                });
            });


        }

    </script>

@stop
