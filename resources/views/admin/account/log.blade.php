@extends('admin._layoutNew')
@section('page_head')
@stop

@section('page-content')
    <div class="layui-form">
        <div class="layui-inline">
            <label class="layui-form-label">账号</label>
            <div class="layui-input-inline">
                <input type="text" name="account" placeholder="请输入账号" autocomplete="off" class="layui-input" value="">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">姓名</label>
            <div class="layui-input-inline">
                <input type="text" name="username" placeholder="请输入姓名" autocomplete="off" class="layui-input" value="">
            </div>
        </div>
        <button class="layui-btn btn-search" id="search" lay-submit lay-filter="search"> <i class="layui-icon">&#xe615;</i> </button>
    </div>

    <table class="layui-hide" id="logList" lay-filter="logList"></table>

@stop
@section('scripts')
    <script type="text/javascript">
        window.onload = function () {
            layui.use(['layer', 'table','form','laydate'], function () { //独立版的layer无需执行这一句
                var $ = layui.jquery;
                var layer = layui.layer; //独立版的layer无需执行这一句
                var table = layui.table;
                var form = layui.form;

                function tbRend(url) {
                    table.render({
                        elem: '#logList',
                        url: url,
                        page: true,
                        cols: [[
                            {field: 'id', title: 'ID', width: 50},
                            {field: 'account', title: '账号', minWidth: 100},
                            {field: 'username', title: '姓名', minWidth: 100},
                            {field: 'ip', title: 'IP地址', minWidth: 100},
                            {field: 'address', title: '登录地址', minWidth: 150},
                            {field: 'create_time', title: '登录时间', minWidth: 150},
                        ]]
                    });
                }
                tbRend("{{url('/admin/login_list')}}");
                $("#search").click(function() {
                    var account = $("input[ name='account']").val();
                    var username = $("input[ name='username']").val();
                    tbRend("{{url('/admin/login_list')}}?account=" + account + "&username=" + username);
                    return false;
                });
            });


        }

    </script>

@stop