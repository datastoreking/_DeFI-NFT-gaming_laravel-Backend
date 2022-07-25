@extends('admin._layoutNew')
@section('page-head')

@endsection
@section('page-content')
	<form class="layui-form" method="POST">
		<div class="layui-form-item">
			<label class="layui-form-label">手机号</label>
			<div class="layui-input-block">
				<input class="layui-input" name="mobile"  placeholder="请输入手机号" type="text" value="{{$user['mobile']}}">
			</div>
		</div>
        <div class="layui-form-item">
            <label class="layui-form-label">昵称</label>
            <div class="layui-input-block">
                <input class="layui-input" name="nickname"  placeholder="请输入昵称" type="text" value="{{$user['nickname']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">用户等级</label>
            <div class="layui-input-block">
                <select name="level" id="level" lay-filter="level">
                    <option value="0" @if(isset($user['level']) && $user['level'] == 0){{'selected="selected"'}} @endif>游客</option>
                    <option value="1" @if(isset($user['level']) && $user['level'] == 1){{'selected="selected"'}} @endif>顾客</option>
                    <option value="2" @if(isset($user['level']) && $user['level'] == 2){{'selected="selected"'}} @endif>店主</option>
                    <option value="3" @if(isset($user['level']) && $user['level'] == 3){{'selected="selected"'}} @endif>一星店主</option>
                    <option value="4" @if(isset($user['level']) && $user['level'] == 3){{'selected="selected"'}} @endif>二星店主</option>
                    <option value="5" @if(isset($user['level']) && $user['level'] == 3){{'selected="selected"'}} @endif>三星店主</option>
                    <option value="6" @if(isset($user['level']) && $user['level'] == 3){{'selected="selected"'}} @endif>四星店主</option>
                    <option value="7" @if(isset($user['level']) && $user['level'] == 3){{'selected="selected"'}} @endif>五星店主</option>
                </select>
            </div>
        </div>
		<div class="layui-form-item">
			<label class="layui-form-label">密码</label>
			<div class="layui-input-block">
				<input class="layui-input" name="password"  placeholder="修改用户时可留空" type="text" value="">
			</div>
		</div>
		<input type="hidden" name="id" value="{{$user['id']}}">
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
layui.use(['form','upload','layer','laydate'], function () {
	var layer = layui.layer;
	var form = layui.form;
	var $ = layui.$;
	var laydate = layui.laydate;
	laydate.render({
		elem: '#expire_time',
		type: 'datetime'
	});
	form.on('submit(submit)', function (data) {
		var data = data.field;
		$.ajax({
			url:'/admin/user_add',
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
