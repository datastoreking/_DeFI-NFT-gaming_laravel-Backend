@extends('admin._layoutNew')
@section('page-head')

@endsection
@section('page-content')
    <form class="layui-form" method="POST">

        <div class="layui-form-item">
            <label class="layui-form-label">权限名称</label>
            <div class="layui-input-block">
                <input class="layui-input" placeholder="请填写权限名称" type="text" value="{{$auth->title}}">
            </div>
        </div>
        <div style="display: none" data-value="{{$menuList}}" id="menuList"></div>
        <input class="layui-input" id="auth_id" name="id" type="hidden" value="{{$auth->id}}">
        <div class="layui-form-item">
            <label class="layui-form-label">权限名称</label>
            <div class="layui-input-block">
                <div id="node-tree" class="demo-tree"></div>
            </div>
        </div>

        <div id="test7" class="demo-tree"></div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="submit">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
    <script>

        layui.use(['form','upload','layer','tree','util'], function () {
            var layer = layui.layer;
            var form = layui.form;
            var $ = layui.$;
            var str = $('#menuList').attr('data-value');
            var tree = layui.tree
                //权限数据
                ,node = JSON.parse(str);
            //开启复选框
            console.log('权限节点',node)
            tree.render({
                elem: '#node-tree'
                ,data: node
                ,showCheckbox: true
            });

            form.on('submit(submit)', function (data) {
                var data2 = data.field;
                $.ajax({
                    url:'/admin/auth_node',
                    type: 'post',
                    dataType: 'json',
                    data: data2,
                    success: function (res) {

                        if(res.type == 'success') {
                            layer.msg(res.msg,{icon:1,time:2000},function () {
                                var index = parent.layer.getFrameIndex(window.name);
                                parent.layer.close(index);
                                parent.window.location.reload();
                            });
                        }else{
                            layer.msg(res.msg,{icon:2,time:2000})
                            return false;
                        }
                    }
                });
                return false;
            });


        });
    </script>
@endsection
