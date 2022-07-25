@extends('admin._layoutNew')
@section('page-head')

@endsection
@section('page-content')
    <form class="layui-form" method="POST">
        <div class="layui-form-item">
            <label class="layui-form-label">编号</label>
            <div class="layui-input-block">
                <input class="layui-input" name="number"  placeholder="请输入编号" type="text" value="">
            </div>
        </div>
        <input type="hidden" name="box_id" id="box_id" value="{{$box_id}}">
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="submit">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
        <div class="layui-form-item" >
            <label class="layui-form-label">编号记录</label>
            <div class="layui-input-block" >
                <table class="layui-table">
                    <thead>
                    <tr id="tr_ajax">
                        <th>编号</th>
                        <th>是否出奖</th>
                        <th>时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($logList as $k=>$v)
                        <tr>
                            <td>{{$v['number']}}</td>
                            <td>@if($v['is_award'] == 0)未出奖@else已出奖@endif</td>
                            <td>{{$v['create_time ']}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
    <script>
        layui.use(['form','upload','layer'], function () {
            var layer = layui.layer;
            var form = layui.form;
            var $ = layui.$;

            form.on('submit(submit)', function (data) {
                var data = data.field;
                $.ajax({
                    url:'/admin/box_three_prize',
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
@endsection
