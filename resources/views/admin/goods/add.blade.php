@extends('admin._layoutNew')
@section('page-head')

@endsection
@section('page-content')
    <form class="layui-form" method="POST">
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-block">
                <input class="layui-input" name="name" placeholder="请输入名称" type="text" value="{{$goods['name']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">图片</label>
            <div class="layui-input-block">
                <button class="layui-btn" type="button" id="upload_img">选择图片</button>
                <br/>
                <img src="@if(!empty($goods['image'])){{$goods['image']}}@endif" class="image" style="display: @if(!empty($goods['image'])){{"block"}}@else{{"none"}}@endif;max-width: 200px;height: auto;margin-top: 5px;">
                <input type="hidden" name="image" id="image" value="@if(!empty($goods['image'])){{$goods['image']}}@endif">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">售价</label>
            <div class="layui-input-block">
                <input class="layui-input" name="price" placeholder="请输入售价" type="text" value="{{$goods['price']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">回收价</label>
            <div class="layui-input-block">
                <input class="layui-input" name="cost_price" placeholder="投入回收百分比" type="text" value="{{$goods['cost_price']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否预售</label>
            <div class="layui-input-block">
                <input type="radio" name="is_book" value="0" title="否" {{$goods['is_book'] == 0?'checked':''}}>
                <input type="radio" name="is_book" value="1" title="是" {{$goods['is_book'] == 1?'checked':''}}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">预售日期</label>
            <div class="layui-input-block">
                <input class="layui-input" name="book_time" id="book_time" placeholder="请选择预售日期" type="text" value="{{$goods['book_time']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序值</label>
            <div class="layui-input-block">
                <input class="layui-input" name="sort" placeholder="请输入排序值" type="text" value="{{1}}" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">NFT 合约地址</label>
            <div class="layui-input-block">
                <input class="layui-input" name="contract_address" placeholder="如果这是 NFT 奖励，请输入 NFT 合约地址" type="text" value="{{$goods['contract_address']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">奖励类型</label>
            <div class="layui-input-block">
                <input class="layui-input" name="reward_type" placeholder="输入奖励类型" type="text" value="{{$goods['reward_type']}}">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">详情内容</label>
            <div class="layui-input-block">
                <script id="content" name="content" type="text/plain" style="min-width: 800px;height: 200px;">
                    @if(isset($goods['content'])){!! $goods['content'] !!}@endif
                </script>
                </div>
                </div>
        <input type="hidden" name="id" value="{{$goods['id']}}">
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="submit">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
                    <script type="text/javascript" src="{{ URL('ueditor/1.4.3/ueditor.config.js') }}"></script>
                <script type="text/javascript" src="{{ URL('ueditor/1.4.3/ueditor.all.js') }}"> </script>
                <script type="text/javascript" src="{{ URL('ueditor/1.4.3/lang/zh-cn/zh-cn.js') }}"></script>
    <script>
        layui.use(['form','upload','layer','laydate'], function () {
            var layer = layui.layer;
            var form = layui.form;
            var $ = layui.$;
            var upload = layui.upload;
            var laydate = layui.laydate;
            laydate.render({
                elem: '#book_time',
                type: 'datetime'
            });
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
            var ue = UE.getEditor('content');
            form.on('submit(submit)', function (data) {
                var data = data.field;
                data.content = ue.getContent();
                $.ajax({
                    url:'/admin/goods_add',
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
