@extends('admin._layoutNew')
@section('page-head')
    <style>
        .layui-form-label{
            width: 120px;
        }
        .layui-input{
            width: 700px;
        }
    </style>
@endsection
@section('page-content')
    <form class="layui-form" method="POST">
        <div class="layui-form-item">
            <label class="layui-form-label">网站名称</label>
            <div class="layui-input-block">
                <input class="layui-input" name="site_name"  placeholder="请输入网站名称" type="text" value="{{$data['site_name']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">网站域名</label>
            <div class="layui-input-block">
                <input class="layui-input" name="site_domain"  placeholder="请输入网站域名" type="text" value="{{$data['site_domain']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">网站LOGO</label>
            <div class="layui-input-block">
                <button class="layui-btn" type="button" id="upload_logo">选择图片</button>
                <br/>
                <img src="@if(!empty($data['site_logo'])){{URL($data['site_logo'])}}@endif" class="site_logo" style="display: @if(!empty($data['site_logo'])){{"block"}}@else{{"none"}}@endif;max-width: 200px;height: auto;margin-top: 5px;margin-left: 40px;">
                <input type="hidden" name="site_logo" id="site_logo" value="@if(!empty($data['site_logo'])){{$data['site_logo']}}@endif">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">发货说明</label>
            <div class="layui-input-block">
                <input class="layui-input" name="site_deliver"  placeholder="请输入发货说明" type="text" value="{{$data['site_deliver']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">系统邮费</label>
            <div class="layui-input-block">
                <input class="layui-input" name="site_freight"  placeholder="请输入系统邮费" type="text" value="{{$data['site_freight']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">免邮费数量</label>
            <div class="layui-input-block">
                <input class="layui-input" name="site_free_num"  placeholder="请输入免邮费数量" type="text" value="{{$data['site_free_num']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">系统公告</label>
            <div class="layui-input-block">
                <input class="layui-input" name="site_notice"  placeholder="请输入系统公告" type="text" value="{{$data['site_notice']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">特殊赏返回比例</label>
            <div class="layui-input-block">
                <input class="layui-input" name="box_free"  placeholder="请输入特殊赏返回比例" type="text" value="{{$data['box_free']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">潮玩券消费金额</label>
            <div class="layui-input-block">
                <input class="layui-input" name="coupon_achieve"  placeholder="请输入消费多少金额可得潮玩券" type="text" value="{{$data['coupon_achieve']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">福利群</label>
            <div class="layui-input-block">
                <button class="layui-btn" type="button" id="upload_group">选择图片</button>
                <br/>
                <img src="@if(!empty($data['site_group'])){{URL($data['site_group'])}}@endif" class="site_group" style="display: @if(!empty($data['site_group'])){{"block"}}@else{{"none"}}@endif;max-width: 200px;height: auto;margin-top: 5px;margin-left: 40px;">
                <input type="hidden" name="site_group" id="site_group" value="@if(!empty($data['site_group'])){{$data['site_group']}}@endif">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">分享图片</label>
            <div class="layui-input-block">
                <button class="layui-btn" type="button" id="upload_share">选择图片</button>
                <br/>
                <img src="@if(!empty($data['site_share'])){{URL($data['site_share'])}}@endif" class="site_share" style="display: @if(!empty($data['site_share'])){{"block"}}@else{{"none"}}@endif;max-width: 200px;height: auto;margin-top: 5px;margin-left: 40px;">
                <input type="hidden" name="site_share" id="site_share" value="@if(!empty($data['site_share'])){{$data['site_share']}}@endif">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否送券</label>
            <div class="layui-input-block">
                <input type="radio" name="is_coupon" value="1" title="是" {{$data['is_coupon']?'checked':''}}>
                <input type="radio" name="is_coupon" value="0" title="否" {{!$data['is_coupon']?'checked':''}}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="submit">立即提交</button>
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
            //执行实例
            upload.render({
                elem: '#upload_logo',
                url: '{{URL("api/upload")}}',
                multiple: 'false',
                done: function(res) {
                    if (res.code === 1) {
                        $("#site_logo").val(res.data.filename)
                        $(".site_logo").show()
                        $(".site_logo").attr("src", res.data.filepath)
                    } else {
                        alert(res.msg)
                    }
                },
                error: function() {
                    //请求异常回调
                }
            });
            upload.render({
                elem: '#upload_group',
                url: '{{URL("api/upload")}}',
                multiple: 'false',
                done: function(res) {
                    if (res.code === 1) {
                        $("#site_group").val(res.data.filename)
                        $(".site_group").show()
                        $(".site_group").attr("src", res.data.filepath)
                    } else {
                        alert(res.msg)
                    }
                },
                error: function() {
                    //请求异常回调
                }
            });
            upload.render({
                elem: '#upload_share',
                url: '{{URL("api/upload")}}',
                multiple: 'false',
                done: function(res) {
                    if (res.code === 1) {
                        $("#site_share").val(res.data.filename)
                        $(".site_share").show()
                        $(".site_share").attr("src", res.data.filepath)
                    } else {
                        alert(res.msg)
                    }
                },
                error: function() {
                    //请求异常回调
                }
            });
            form.on('submit(submit)', function (data) {
                $.ajax({
                    url:'/admin/config_save',
                    type: 'post',
                    dataType: 'json',
                    data: data.field,
                    success: function (res) {
                        if(res.type === 'success') {
                            layer.msg(res.msg,{icon: 1,time:2000},function () {
                                window.location.reload();
                            });
                        }else{
                            layer.msg(res.message);
                            return false;
                        }
                    }
                });
                return false;
            });


        });
    </script>
@endsection
