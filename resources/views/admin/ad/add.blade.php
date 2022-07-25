@extends('admin._layoutNew')
@section('page-head')

@endsection
@section('page-content')
	<form class="layui-form" method="POST">
		<div class="layui-form-item">
			<label class="layui-form-label">图片</label>
			<div class="layui-input-block">
				<button class="layui-btn" type="button" id="upload_img">选择图片</button>
				<br/>
				<img src="@if(!empty($ad['image'])){{$ad['image']}}@endif" class="image" style="display: @if(!empty($ad['image'])){{"block"}}@else{{"none"}}@endif;max-width: 200px;height: auto;margin-top: 5px;">
				<input type="hidden" name="image" id="image" value="@if(!empty($ad['image'])){{$ad['image']}}@endif">
			</div>
		</div>
        <div class="layui-form-item">
            <label class="layui-form-label">跳转链接</label>
            <div class="layui-input-block">
                <input class="layui-input" name="url"  placeholder="请输入跳转链接" type="text" value="{{$ad['url']}}">
            </div>
        </div>
		<div class="layui-form-item">
			<label class="layui-form-label">类型</label>
			<div class="layui-input-block">
				<select name="type" id="type">
					<option value="1" @if(isset($ad['type']) && $ad['type'] == 1) {{'selected="selected"'}} @endif>首页轮播</option>
					<option value="2" @if(isset($ad['type']) && $ad['type'] == 2) {{'selected="selected"'}} @endif>个人中心</option>
				</select>
			</div>
		</div>
		<input type="hidden" name="id" value="{{$ad['id']}}">
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
	form.on('submit(submit)', function (data) {
		var data = data.field;
		$.ajax({
			url:'/admin/ad_add',
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
