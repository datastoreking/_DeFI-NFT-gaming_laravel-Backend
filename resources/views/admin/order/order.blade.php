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
        <label class="layui-form-label">赏品名</label>
        <div class="layui-input-inline">
            <input type="text" name="name" placeholder="请输入赏品名" autocomplete="off" class="layui-input" value="">
        </div>
    </div>
    <div class="layui-inline">
        <label class="layui-form-label">订单状态</label>
        <div class="layui-input-inline">
            <select name="status" id="status">
                <option value="0">请选择状态</option>
                <option value="1">未申请</option>
                <option value="2">保险柜</option>
                <option value="3">已申请</option>
                <option value="4">已发货</option>
                <option value="5">已挂售</option>
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
    @{{ d.status == 1 ? '<span class="layui-badge layui-bg-green">未申请</span>' : '' }}
    @{{ d.status == 2 ? '<span class="layui-badge layui-bg-blue">保险柜</span>' : '' }}
    @{{ d.status == 3 ? '<span class="layui-badge layui-bg-orange">已申请</span>' : '' }}
    @{{ d.status == 4 ? '<span class="layui-badge layui-bg-gray">已发货</span>' : '' }}
    @{{ d.status == 5 ? '<span class="layui-badge layui-bg-red">已挂售</span>' : '' }}
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
                          {field: 'status', title: '状态', minWidth: 100, templet: '#statusTpl'},
                          {field: 'name', title: '商品名称', minWidth: 100},
                          {field: 'image', title: '商品图片', minWidth: 150, templet: function(d) {
                                  var html = '<div class="layer-photos-demo" onclick="img_click()" style="cursor:pointer;" >';
                                  html += ' <img layer-pid=""  layer-src="' + d.image + '" src="' + d.image + '" style="width: 80px;height: 38px;">';
                                  html += '</div>';
                                  return html
                              }
                          },
                          {field: 'price', title: '商品价格', minWidth: 100},
                          {field: 'cost_price', title: '回收价格', minWidth: 100},
                          {field: 'create_time', title: '创建时间', minWidth: 100},
                      ]],
                      done: function(res, curr, count) {
                          layer.photos({ //点击图片弹出
                              photos: '.layer-photos-demo'
                              //,anim: 1 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
                          });
                      }
                  });
              }
              tbRend("{{url('/admin/order_goods_list')}}");
              $("#search").click(function() {
                  var nickname = $("input[ name='nickname']").val();
                  var name = $("input[ name='name']").val();
                  var status = $("#status").val();
                  var start_time = $("#start_time").val();
                  var end_time = $("#end_time").val();
                  tbRend("{{url('/admin/order_goods_list')}}?status="+ status +"&nickname=" + nickname +"&name=" + name + "&start_time=" + start_time + "&end_time=" + end_time);
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
