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
        <label class="layui-form-label">订单状态</label>
        <div class="layui-input-inline">
            <select name="state" id="state">
                <option value="0">请选择状态</option>
                <option value="1">已申请</option>
                <option value="2">已发货</option>
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
   <table class="layui-hide" id="userList" lay-filter="userList"></table>
<script type="text/html" id="statusTpl">
    @{{ d.status == 1 ? '<span class="layui-badge layui-bg-green">已申请</span>' : '' }}
    @{{ d.status == 2 ? '<span class="layui-badge layui-bg-blue">已发货</span>' : '' }}
</script>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="deliver">发货</a>
</script>
  @stop
  @section('scripts')
      <script type="text/javascript">
          window.onload = function() {
          layui.use(['layer', 'table', 'form', 'laydate'], function() { //独立版的layer无需执行这一句
              var $ = layui.jquery;
              var layer = layui.layer; //独立版的layer无需执行这一句
              var table = layui.table;
              var form = layui.form;
              var laydate = layui.laydate;
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
                      elem: '#userList',
                      url: url,
                      page: true,
                      cols: [[
                          {field: 'id',title: 'ID', minWidth: 50},
                          {field: 'nickname', title: '昵称', minWidth: 100},
                          {field: 'state', title: '状态', minWidth: 100, templet: '#statusTpl'},
                          {field: 'username', title: '收货人', minWidth: 100},
                          {field: 'mobile', title: '手机号', minWidth: 100},
                          {field: 'address', title: '收货地址', minWidth: 150},
                          {field: 'remark', title: '备注', minWidth: 100},
                          {field: 'express_name', title: '物流名称', minWidth: 100},
                          {field: 'express_number', title: '物流单号', minWidth: 100},
                          {field: 'create_time', title: '创建时间', minWidth: 100},
                          {field: 'right', title: '操作', minWidth: 100, align: 'center', toolbar: '#barDemo'}
                      ]]
                  });
              }
              tbRend("{{url('/admin/order_deliver_list')}}");
              $("#search").click(function() {
                  var nickname = $("input[ name='nickname']").val();
                  var state = $("#state").val();
                  var start_time = $("#start_time").val();
                  var end_time = $("#end_time").val();
                  tbRend("{{url('/admin/order_deliver_list')}}?state="+ state +"&nickname=" + nickname + "&start_time=" + start_time + "&end_time=" + end_time);
                  return false;
              });
              //监听工具条
              table.on('tool(userList)', function(obj) { //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
                  var data = obj.data; //获得当前行数据
                  var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
                  var tr = obj.tr; //获得当前行 tr 的DOM对象

                  if(layEvent === 'deliver'){
                      layer_show('订单发货', '/admin/order_deliver?id=' + data.id,800,600);
                  }
              });
          });
      }
    </script>

  @stop
