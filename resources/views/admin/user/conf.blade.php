@extends('admin._layoutNew')
@section('page-head')

@endsection
@section('page-content')
	<form class="layui-form" method="POST">
		<div class="layui-form-item">
			<label class="layui-form-label">昵称</label>
			<div class="layui-input-block">
				<input class="layui-input" type="text" value="{{$user['nickname']}}" disabled>
			</div>
		</div>
        <div class="layui-form-item">
            <label class="layui-form-label">余额</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" value="{{$user['balance']}}" disabled>
            </div>
        </div>
		<div class="layui-form-item">
			<label class="layui-form-label">潮玩币</label>
			<div class="layui-input-block">
				<input class="layui-input" type="text" value="{{$user['score']}}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">调整类型</label>
			<div class="layui-input-block">
				<input type="radio" name="type" value="1" title="余额" >
				<input type="radio" name="type" value="2" title="潮玩币" >
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">金额</label>
			<div class="layui-input-block">
				<input class="layui-input" name="value"  placeholder="请输入要调整的数值" type="text" value="">
			</div>
		</div>
        <div class="layui-form-item">
            <label class="layui-form-label">调整方式</label>
            <div class="layui-input-block">
                <input type="radio" name="way" value="1" title="增加" >
                <input type="radio" name="way" value="2" title="扣除" >
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
        var loading = layer.load(0, {
            shade: [0.3, '#393D49'],
        });
		var data = data.field;
		$.ajax({
			url:'/admin/user_conf',
			type: 'post',
			dataType: 'json',
			data: data,
			success: function (res) {
                layer.close(loading);
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
