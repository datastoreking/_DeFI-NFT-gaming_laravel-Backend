@extends('admin._layoutNew')
@section('page-head')

@stop
@section('page-content')
    <div class="larry-personal-body clearfix">
        <form class="layui-form col-lg-5">
            <div class="layui-form-item">
                <label class="layui-form-label">角色名称</label>
                <div class="layui-input-block">
                    <input type="text" name="name" autocomplete="off" class="layui-input" value="{{ $adminRole['name'] }}" placeholder="请输入角色名称">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">角色描述</label>
                <div class="layui-input-block">
                    <input type="text" name="describe" autocomplete="off" class="layui-input" value="{{ $adminRole['describe'] }}" placeholder="请输入角色名称">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">超级管理员</label>
                <div class="layui-input-block">
                    <input type="radio" name="is_super" value="1" title="是" {{$adminRole['is_super']?'checked':''}}>
                    <input type="radio" name="is_super" value="0" title="否" {{!$adminRole['is_super']?'checked':''}}>
                </div>
            </div>


            <input type="hidden" name="id" value="{{$adminRole['id']}}">
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="adminrole_submit">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
@stop
@section('scripts')
    <script type="text/javascript">

        layui.use(['form','upload','layer'], function () {
            var layer = layui.layer;
            var form = layui.form;
            form.on('submit(adminrole_submit)', function (data) {
                var data = data.field;
                $.ajax({
                    url: '/admin/role_add',
                    type: 'post',
                    dataType: 'json',
                    data: data,
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
@stop