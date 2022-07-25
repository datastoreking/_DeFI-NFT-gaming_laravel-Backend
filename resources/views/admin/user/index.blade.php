@extends('admin._layoutNew')
@section('page_head')

@stop

@section('page-content')

<div class="layui-form">
    <div class="layui-inline">
        <label class="layui-form-label">用户ID</label>
        <div class="layui-input-inline">
            <input type="text" name="uid" placeholder="请输入用户ID" autocomplete="off" class="layui-input" value="">
        </div>
    </div>
    <div class="layui-inline">
        <label class="layui-form-label">用户昵称</label>
        <div class="layui-input-inline">
            <input type="text" name="nickname" placeholder="请输入用户昵称" autocomplete="off" class="layui-input" value="">
        </div>
    </div>
    <div class="layui-inline">
        <label class="layui-form-label">注册时间</label>
        <div class="layui-input-inline">
            <input type="text" name="create_time" id="create_time" placeholder="请选择注册时间" autocomplete="off" class="layui-input" value="" >
        </div>
    </div>
    <div class="layui-inline">
        <label class="layui-form-label">账号状态</label>
        <div class="layui-input-inline">
            <select name="state">
                <option value="0">请选择状态</option>
                <option value="1">正常</option>
                <option value="2">封号中</option>
            </select>
        </div>
    </div>
    <button class="layui-btn btn-search" id="search" lay-submit lay-filter="mobile_search"> <i class="layui-icon">&#xe615;</i> </button>
</div>
   <table class="layui-hide" id="userList" lay-filter="userList"></table>
   <script type="text/html" id="stateTpl">
      <input type="checkbox" name="state" value="@{{d.id}}" lay-skin="switch" lay-text="开|关" lay-filter="state" @{{  d.state == 2 ? 'checked' : '' }}>
   </script>
    <script type="text/html" id="stateNameTpl">
        @{{ d.state == 1 ? '<span class="layui-badge layui-bg-green">正常</span>' : '' }}
        @{{ d.state == 2 ? '<span class="layui-badge ">封号中</span>' : ''}}
    </script>
  <script type="text/html" id="barDemo">
      <a class="layui-btn layui-btn-xs layui-bg-green" lay-event="conf">充值</a>
      <a class="layui-btn layui-btn-xs layui-bg-red" lay-event="del">删除</a>
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
                  elem: '#create_time',
                  type: 'date'
              });
              $('#add_user').click(function(){layer_show('新增用户', '/admin/user_add',800,500);});
              function tbRend(url) {
                  table.render({
                      elem: '#userList',
                      url: url,
                      page: true,
                      cols: [[
                          {field: 'id',title: 'ID', minWidth: 50},
                          {field: 'avatar', title: '头像', minWidth: 150, templet: function(d) {
                              var html = '<div class="layer-photos-demo" onclick="img_click()" style="cursor:pointer;" >';
                              html += ' <img layer-pid=""  layer-src="' + d.avatar + '" src="' + d.avatar + '" style="width: 80px;height: 38px;">';
                              html += '</div>';
                              return html
                            }
                          },
                          {field: 'nickname', title: '昵称', minWidth: 100},
                          {field: 'balance', title: '余额', minWidth: 100},
                          {field: 'score', title: '潮玩币', minWidth: 100},
                          {field: 'create_time', title: '注册时间', minWidth: 150},
                          {field: 'state', title: '账号状态', minWidth: 100, templet: '#stateNameTpl'},
                          {field: 'state', title: '是否封号', minWidth: 100, align: 'center', templet: '#stateTpl'},
                          {field: 'right', title: '操作', minWidth: 100, align: 'center', toolbar: '#barDemo'}
                      ]],
                      done: function(res, curr, count) {
                          layer.photos({ //点击图片弹出
                              photos: '.layer-photos-demo'
                              //,anim: 1 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
                          });
                      }
                  });
              }
              tbRend("{{url('/admin/user_list')}}");
              $("#search").click(function() {
                  var uid = $("input[ name='uid']").val();
                  var nickname = $("input[ name='nickname']").val();
                  var create_time = $("#create_time").val();
                  var state = $('select[name=state]').val();
                  tbRend("{{url('/admin/user_list')}}?uid=" + uid + "&nickname=" + nickname + "&create_time=" + create_time + "&state=" + state);
                  return false;
              });
              form.on('switch(state)', function (obj) {
                  var id = obj.value;
                  $.ajax({
                      url: '{{url('admin/user_state')}}',
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
              table.on('tool(userList)', function(obj) { //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
                  var data = obj.data; //获得当前行数据
                  var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
                  var tr = obj.tr; //获得当前行 tr 的DOM对象

                  if(layEvent === 'edit'){ //编辑
                      //do something
                      layer_show('修改用户', '/admin/user_add?id=' + data.id,800,500);
                  }else if(layEvent === 'conf'){ //编辑
                      //do something
                      layer_show('充值/扣除', '/admin/user_conf?id=' + data.id,800,600);
                  }else if(layEvent === 'del'){ //删除
                      layer.confirm('真的要删除吗？', function(index){
                          //向服务端发送删除指令
                          $.ajax({
                              url:'/admin/user_del',
                              type:'post',
                              dataType:'json',
                              data:{id:data.id},
                              success:function(res){
                                  if(res.type==='success'){
                                      layer.msg(res.msg,{icon:1,time:2000},function () {
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
