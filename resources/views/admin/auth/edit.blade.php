@extends('admin._layoutNew')
@section('page-head')

@endsection
@section('page-content')
	<form class="layui-form" method="POST">

		<div class="layui-form-item">
			<label class="layui-form-label">权限名称</label>
			<div class="layui-input-block">
				<input class="layui-input" name="title" @if(isset($auth->id)){{$auth->id == 8 ? 'readonly' : ''}}@endif placeholder="请填写权限名称" type="text"  value="{{$auth->title}}">
			</div>
		</div>

        <input class="layui-input" name="id" type="hidden" value="{{$auth->id}}">
		<div class="layui-form-item">
			<label class="layui-form-label">备注信息</label>
			<div class="layui-input-block">
				<input class="layui-input" name="remark"  placeholder="请填写备注信息" type="text" value="{{$auth->remark}}">
			</div>
		</div>
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
			url:'/admin/auth_edit',
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
