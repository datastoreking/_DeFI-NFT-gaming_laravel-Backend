@extends('admin._layoutNew')
@section('page_head')
@stop

@section('page-content')
    <div class="layui-form">
        <div class="layui-inline">
            <label class="layui-form-label">昵称</label>
            <div class="layui-input-inline">
                <input type="text" name="nickname" placeholder="请输入昵称" autocomplete="off" class="layui-input" value="">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-inline">
                <select name="state" id="state">
                    <option value="0">请选择状态</option>
                    <option value="1">提现中</option>
                    <option value="2">提现成功</option>
                    <option value="3">提现失败</option>
                </select>
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">开始时间</label>
            <div class="layui-input-inline">
                <input type="text" name="start_time" id="start_time" placeholder="请选择开始时间" autocomplete="off" class="layui-input" value="" >
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">结束时间</label>
            <div class="layui-input-inline">
                <input type="text" name="end_time" id="end_time" placeholder="请选择结束时间" autocomplete="off" class="layui-input" value="" >
            </div>
        </div>
        <button class="layui-btn btn-search" id="search" lay-submit lay-filter="mobile_search"> <i class="layui-icon">&#xe615;</i> </button>
    </div>
    <table class="layui-hide" id="cashList" lay-filter="cashList"></table>
    <script type="text/html" id="stateTpl">
        @{{ d.state == 0 ? '<span class="layui-badge layui-bg-orange">提现中</span>' : '' }}
        @{{ d.state == 1 ? '<span class="layui-badge layui-bg-green">提现成功</span>' : ''}}
        @{{ d.state == 2 ? '<span class="layui-badge layui-bg-red">提现失败</span>' : ''}}
    </script>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="pass">通过</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="back">拒绝</a>
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
                var laydate = layui.laydate
                laydate.render({
                    elem: '#start_time',
                    type: 'date'
                });
                laydate.render({
                    elem: '#end_time',
                    type: 'date'
                });
                function tbRend(url) {
                    table.render({
                        elem: '#cashList',
                        url: url,
                        page: true,
                        cols: [[
                            {field: 'id', title: 'ID', minWidth: 80},
                            {field: 'nickname', title: '昵称', minWidth: 100},
                            {field: 'amount', title: '提现金额', minWidth: 100},
                            {field: 'service_fee', title: '手续费', minWidth: 100},
                            {field: 'real_amount', title: '实到金额', minWidth: 100},
                            {field: 'name', title: '姓名', minWidth: 100},
                            {field: 'qrcode', title: '收款码', minWidth: 150, templet: function(d) {
                                    var html = '<div class="layer-photos-demo" onclick="img_click()" style="cursor:pointer;" >';
                                    html += ' <img layer-pid=""  layer-src="' + d.qrcode + '" src="' + d.qrcode + '" style="width: 80px;height: 38px;">';
                                    html += '</div>';
                                    return html
                                }
                            },
                            {field: 'state', title: '状态', minWidth: 100, templet: '#stateTpl'},
                            {field: 'create_time', title: '申请时间', minWidth: 150},
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
                tbRend("{{url('/admin/cash_list')}}");
                $("#search").click(function() {
                    var nickname = $("input[ name='nickname']").val();
                    var state = $("#state").val();
                    var start_time = $("#start_time").val();
                    var end_time = $("#end_time").val();
                    tbRend("{{url('/admin/cash_list')}}?nickname=" + nickname + "&state=" + state + "&start_time=" + start_time + "&end_time=" + end_time);
                    return false;
                });
                //监听工具条
                table.on('tool(cashList)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data; //获得当前行数据
                    var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
                    var tr = obj.tr; //获得当前行 tr 的DOM对象
                    if(layEvent === 'pass'){ //删除
                        layer.confirm('确定审核通过吗？', function(index){
                            //向服务端发送删除指令
                            $.ajax({
                                url:'/admin/cash_pass',
                                type:'post',
                                dataType:'json',
                                data:{id:data.id},
                                success:function(res){
                                    if(res.type==='success'){
                                        layer.msg(res.msg,{icon: 1,time:2000},function () {
                                            window.location.reload();
                                        });
                                    }else{
                                        layer.close(index);
                                        layer.alert(res.msg);
                                    }
                                }
                            });
                        });
                    }else if(layEvent === 'back'){ //删除
                        layer.confirm('确定审核拒绝吗？', function(index){
                            //向服务端发送删除指令
                            $.ajax({
                                url:'/admin/cash_back',
                                type:'post',
                                dataType:'json',
                                data:{id:data.id},
                                success:function(res){
                                    if(res.type==='success'){
                                        layer.msg(res.msg,{icon: 1,time:2000},function () {
                                            window.location.reload();
                                        });
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
