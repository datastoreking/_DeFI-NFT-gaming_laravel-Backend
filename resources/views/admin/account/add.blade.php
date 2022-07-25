@extends('admin._layoutNew')
@section('page-head')

@endsection
@section('page-content')
	<form class="layui-form" method="POST">

		<div class="layui-form-item">
			<label class="layui-form-label">账号</label>
			<div class="layui-input-block">
				<input class="layui-input" name="username"  placeholder="请输入账号" type="text" value="{{$admin['username']}}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">密码</label>
			<div class="layui-input-block">
				<input class="layui-input" name="password"  placeholder="请输入密码" type="text" value="{{$admin['password']}}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">备注信息</label>
			<div class="layui-input-block">
				<input class="layui-input" name="remark"  placeholder="请填写备注信息" type="text" value="{{$admin['remark']}}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">手机号</label>
			<div class="layui-input-block">
				<input class="layui-input" name="phone"  placeholder="请输入手机号" type="text" value="{{$admin['phone']}}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">角色选择</label>
			<div class="layui-input-block">
				<select name="auth_ids" id="auth_ids" lay-filter="auth_ids">
					<option value="0">请选择角色</option>
					@foreach ($roleList as $role)
						<option @if(isset($admin['auth_ids']) && $admin['auth_ids'] == $role['id']){{'selected="selected"'}}@endif value="{{$role['id']}}">{{$role['title']}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<input type="hidden" name="id" value="{{$admin['id']}}">
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
			url:'/admin/admin_add',
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
