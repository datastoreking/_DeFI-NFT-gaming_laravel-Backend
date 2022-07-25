@extends('admin._layoutNew')
@section('page_head')
@stop

@section('page-content')
    <script type="text/html" id="toolbarHeader">
        <div class="layui-btn-container">
            <button class="layui-btn layuiadmin-btn-admin layui-btn-sm "  lay-event="add" >确认选择</button>
        </div>
    </script>
    <div class="layui-form">
        <div class="layui-inline">
            <label class="layui-form-label">赏品ID</label>
            <div class="layui-input-inline">
                <input type="text" name="id" placeholder="请输入赏品ID" autocomplete="off" class="layui-input" value="">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">赏品名称</label>
            <div class="layui-input-inline">
                <input type="text" name="name" placeholder="请输入赏品名称" autocomplete="off" class="layui-input" value="">
            </div>
        </div>
        <button class="layui-btn btn-search" id="search" lay-submit lay-filter="mobile_search"> <i class="layui-icon">&#xe615;</i> </button>
    </div>
    <table class="layui-hide" id="phoneList" lay-filter="phoneList"></table>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="add">确认选择</a>
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
                        elem: '#phoneList',
                        url: url,
                        toolbar: '#toolbarHeader',
                        defaultToolbar: [],
                        page: true,
                        cols: [[
                            {type:'checkbox'},
                            {field: 'id', title: 'ID', width: 100},
                            {field: 'name', title: '名称', minWidth: 100},
                            {field: 'sort', title: '排序值', minWidth: 100},
                            {field: 'image', title: '图片', minWidth: 150, templet: function(d) {
                                    var html = '<div class="layer-photos-demo" onclick="img_click()" style="cursor:pointer;" >';
                                    html += ' <img layer-pid=""  layer-src="' + d.image + '" src="' + d.image + '" style="width: 80px;height: 38px;">';
                                    html += '</div>';
                                    return html
                                }
                            },
                            {field: 'price', title: '售价', minWidth: 100},
                            {field: 'cost_price', title: '成本价', minWidth: 100},
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
                tbRend("{{url('/admin/goods_three_search')}}");
                $("#search").click(function() {
                    var id = $("input[ name='id']").val();
                    var name = $("input[ name='name']").val();
                    tbRend("{{url('/admin/goods_three_search')}}?goods_id="+ id + "&goods_name=" + name);
                    return false;
                });
                table.on('tool(phoneList)', function(obj){
                    var data = obj.data;
                    if(obj.event === 'add') {
                        var _tr = '';
                        _tr += "<tr>" +
                            "<td class='ids'>"+data.id+"</td>" +
                            "<td>"+data.name+"<input type='hidden' name='goods_id[]' value='"+data.id+"'></td>" +
                            "<td>"+data.sort+"</td><td><img src='"+data.image+"' style='width-max: 150px;height:50px;margin-left: 2px;'></td>" +
                            "<td>"+data.price+"</td><td>"+data.cost_price+"</td>" +
                            "<td><input type='text' style='width: 120px;' class='ratio' name='ratio[]'></td>"+
                            "<td><input type='text' style='width: 120px;' class='level' name='level[]'></td>"+
                            "<td><a onclick='sea_del(this)'>删除</a></td>" +
                            "</tr>"
                        parent.$("#goods_list").append(_tr);
                        parent.layer.close(parent.layer.getFrameIndex(window.name));
                    }
                })
                table.on('toolbar(phoneList)', function(obj){
                    if(obj.event == "add"){
                        var checkStatus = table.checkStatus('phoneList');
                        console.log(checkStatus.data)
                        var _tr = '';
                        $(checkStatus.data).each(function (i, data) {//o即为表格中一行的数据
                            _tr += "<tr>" +
                                "<td class='ids'>"+data.id+"</td>" +
                                "<td>"+data.name+"<input type='hidden' name='goods_id[]' value='"+data.id+"'></td>" +
                                "<td>"+data.sort+"</td><td><img src='"+data.image+"' style='width-max: 150px;height:50px;margin-left: 2px;'></td>" +
                                "<td>"+data.price+"</td><td>"+data.cost_price+"</td>" +
                                "<td><input type='text' style='width: 120px;' class='ratio' name='ratio[]'></td>"+
                                "<td><input type='text' style='width: 120px;' class='level' name='level[]'></td>"+
                                "<td><a onclick='sea_del(this)'>删除</a></td>" +
                                "</tr>"
                        });
                        parent.$("#goods_list").append(_tr);
                        parent.layer.close(parent.layer.getFrameIndex(window.name));
                    }
                })
            });
        }
    </script>
@stop
