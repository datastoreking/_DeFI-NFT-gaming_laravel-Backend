@extends('admin._layoutNew')
@section('page-head')

@endsection
@section('page-content')
	<form class="layui-form" method="POST">
		<div class="layui-form-item">
			<label class="layui-form-label">标题</label>
			<div class="layui-input-block">
				<input class="layui-input" name="title" id="title" placeholder="请输入标题" type="text" value="{{$single['title']}}">
			</div>
		</div>
		<div class="layui-form-item layui-form-text">
			<label class="layui-form-label">详情内容</label>
			<div class="layui-input-block">
				<script id="content" name="content" type="text/plain" style="min-width: 800px;height: 200px;">
					@if(isset($single['content'])){!! $single['content'] !!}@endif
				</script>
			</div>
		</div>


		<input type="hidden" name="id" id="id" value="{{$single['id']}}">
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
layui.use(['form','upload','layer'], function () {
	var layer = layui.layer;
	var form = layui.form;
	var $ = layui.$;
	var ue = UE.getEditor('content');
	form.on('submit(submit)', function (data) {
		$.ajax({
			url:'/admin/single_add',
			type: 'post',
			dataType: 'json',
			data: {
				id:$("#id").val(),
				title:$("#title").val(),
				content:ue.getContent(),
			},
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