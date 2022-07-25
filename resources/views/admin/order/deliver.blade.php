@extends('admin._layoutNew')
@section('page-head')

@endsection
@section('page-content')
    <form class="layui-form" method="POST">
        <div class="layui-form-item">
            <label class="layui-form-label">收货信息</label>
            <div class="layui-input-block">
                <table class="layui-table">
                    <tbody>
                    <tr>
                        <td>收货人：{{$deliver['username']}}</td>
                        <td>手机号：{{$deliver['mobile']}}</td>
                    </tr>
                    <tr>
                        <td colspan="2">收获地址地址：{{$deliver['province'].$deliver['city'].$deliver['area'].$deliver['address']}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商品信息</label>
            <div class="layui-input-block">
                <table class="layui-table">
                    <tbody>
                    @foreach($goodsList as $goods)
                    <tr>
                        <td>商品名称：{{$goods['name']}}</td>
                        <td>数量：{{$goods['num']}}</td>
                    </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">物流方式</label>
            <div class="layui-input-block">
                <select name="express_id" id="express_id">
                    <option value="0">请选择快递</option>
                    @foreach ($expressList as $express)
                        <option @if(isset($deliver['express_id']) && $deliver['express_id'] == $express['id']){{'selected="selected"'}}@endif value="{{$express['id']}}">{{$express['name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">物流单号</label>
            <div class="layui-input-block">
                <input class="layui-input" name="express_number"  placeholder="请输入物流单号" type="text" value="{{$deliver['express_number']}}">
            </div>
        </div>
        <input type="hidden" name="id" value="{{$deliver['id']}}">
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
        layui.use(['form','upload','layer'], function () {
            var layer = layui.layer;
            var form = layui.form;
            var $ = layui.$;

            form.on('submit(submit)', function (data) {
                var data = data.field;
                $.ajax({
                    url:'/admin/order_deliver',
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
