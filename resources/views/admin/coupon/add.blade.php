@extends('admin._layoutNew')
@section('page-head')

@endsection
@section('page-content')
    <form class="layui-form" method="POST">
        <div class="layui-form-item">
            <label class="layui-form-label">级别</label>
            <div class="layui-input-block">
                <input class="layui-input" name="level"  placeholder="请输入级别" type="text" value="{{$coupon['level']}}" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">图片</label>
            <div class="layui-input-block">
                <button class="layui-btn" type="button" id="upload_img">选择图片</button>
                <br/>
                <img src="@if(!empty($coupon['image'])){{$coupon['image']}}@endif" class="image" style="display: @if(!empty($coupon['image'])){{"block"}}@else{{"none"}}@endif;max-width: 200px;height: auto;margin-top: 5px;">
                <input type="hidden" name="image" id="image" value="@if(!empty($coupon['image'])){{$coupon['image']}}@endif">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">合成图片</label>
            <div class="layui-input-block">
                <button class="layui-btn" type="button" id="upload_img_merge">选择图片</button>
                <br/>
                <img src="@if(!empty($coupon['image_merge'])){{$coupon['image_merge']}}@endif" class="image_merge" style="display: @if(!empty($coupon['image_merge'])){{"block"}}@else{{"none"}}@endif;max-width: 200px;height: auto;margin-top: 5px;">
                <input type="hidden" name="image_merge" id="image_merge" value="@if(!empty($coupon['image_merge'])){{$coupon['image_merge']}}@endif">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">最小可得</label>
            <div class="layui-input-block">
                <input class="layui-input" name="min_score"  placeholder="请输入最小可得潮玩币" type="text" value="{{$coupon['min_score']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">最大可得</label>
            <div class="layui-input-block">
                <input class="layui-input" name="max_score"  placeholder="请输入最大可得潮玩币" type="text" value="{{$coupon['max_score']}}">
            </div>
        </div>
        <input type="hidden" name="id" value="{{$coupon['id']}}">
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
            var upload = layui.upload;
            upload.render({
                elem: '#upload_img',
                url: '{{URL("api/upload")}}',
                multiple: 'false',
                done: function(res) {
                    if (res.code === 1) {
                        $("#image").val(res.data.filename)
                        $(".image").show()
                        $(".image").attr("src", res.data.filepath)
                    } else {
                        alert(res.msg)
                    }
                },
                error: function() {
                    //请求异常回调
                }
            });
            upload.render({
                elem: '#upload_img_merge',
                url: '{{URL("api/upload")}}',
                multiple: 'false',
                done: function(res) {
                    if (res.code === 1) {
                        $("#image_merge").val(res.data.filename)
                        $(".image_merge").show()
                        $(".image_merge").attr("src", res.data.filepath)
                    } else {
                        alert(res.msg)
                    }
                },
                error: function() {
                    //请求异常回调
                }
            });
            form.on('submit(submit)', function (data) {
                var data = data.field;
                $.ajax({
                    url:'/admin/coupon_add',
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
