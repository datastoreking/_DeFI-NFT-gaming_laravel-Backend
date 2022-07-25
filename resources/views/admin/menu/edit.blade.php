@extends('admin._layoutNew')
@section('page-head')
<style>
#cateBread {
    border-radius: 2px;
    cursor: pointer;
    background: #fff;
    border: 1px solid #eee;
}

.layui-form-select dl {
    max-height:300px;
}
btn-group {
    position: absolute;
    top: 220px;
}

input[name='icon'] {
    float: left;
    width: calc(100% - 130px);
}
div.logo-box {
    background: #fff;
    border: 1px solid #efefef;
    border-radius: 6px;
    height: 120px;
    width: 120px;
    float: left;
}
.logo-box img.icon {
    display: none;
    width: 100%;
    height: 100%;
}

</style>
@endsection
@section('page-content')
<form class="layui-form">
    <div class="layui-form-item">
        <label class="layui-form-label">上级菜单</label>
        <div class="layui-input-block" id="cateBread">
            <select name="pid" id="pid" lay-verify="required" {{isset($menu->pid) ? 'disabled="disabled"' : 'lay-search'}}>
                <option value="0" @if(isset($menu)){{$menu->pid == 0 ? 'selected' : ''}}@endif>顶级菜单</option>
                @if($menuList)
                    @foreach($menuList as $key => $value)
                    <option value="{{$value['id']}}" @if(isset($menu)){{$menu->pid == $value['id'] ? 'selected' : ''}}@endif>{{$value['title']}}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">分类名称</label>
        <div class="layui-input-block">
        <input type="text" name="title" id="title" placeholder="请输入名称" autocomplete="off" class="layui-input" value="{{isset($menu) ? $menu->title : ''}}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">链接</label>
        <div class="layui-input-block">
            <input type="text" name="href" id="href" placeholder="请输入链接如admin/user_index" autocomplete="off" class="layui-input" value="{{isset($menu) ? $menu->href : ''}}">
        </div>
    </div>
{{--    <div class="layui-form-item">--}}
{{--        <label class="layui-form-label">规则</label>--}}
{{--        <div class="layui-input-block">--}}
{{--            <input category="text" name="href" id="href" placeholder="请输入规则" autocomplete="off" class="layui-input" value="{{isset($category) ? $category['href'] : ''}}">--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="layui-form-item">
        <label class="layui-form-label">排序值</label>
        <div class="layui-input-block">
            <input type="text" name="sort" id="sort" placeholder="请输入排序值" autocomplete="off" class="layui-input" value="{{isset($menu) ? $menu->sort : ''}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">图标</label>
        <div class="layui-input-block">
            <input type="text" name="icon" id="icon" placeholder="icon" autocomplete="off" class="layui-input" value="{{isset($menu) ? $menu->icon : ''}}"><button type="button" id="add-icon" class="layui-btn layui-btn-primary">选择图标</button>
        </div>
    </div>
    <input type="hidden" name="id" id="id" placeholder="请输入排序值" autocomplete="off" class="layui-input" value="{{isset($menu) ? $menu->id : ''}}">

    <div class="layui-form-item">
        <label class="layui-form-label">是否列表</label>
        <div class="layui-input-block">
            <input type="radio" name="menu_status" value="1" title="是" {{$menu->menu_status == 1 ?'checked':''}}>
            <input type="radio" name="menu_status" value="0" title="否" {{$menu->menu_status == 0 ?'checked':''}}>
        </div>
    </div>
{{--    <div class="layui-form-item">--}}
{{--        <label class="layui-form-label">图标</label>--}}
{{--        <div class="layui-input-block">--}}
{{--            <button class="layui-btn" category="button" id="upload_icon">选择图片</button>--}}
{{--            <br>--}}
{{--            <input category="hidden" name="icon" id="icon" value="@if (isset($menu->icon)){{ $menu->icon }}@endif">--}}
{{--            <div class="logo-box">--}}
{{--                <img class="icon" src="@if (!empty($menu->icon)){{ URL($menu->icon) }}@endif" style="display: @if(!empty($category['icon'])){{"block"}}@else{{"none"}}@endif">--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="layui-form-item btn-group">
        <div class="layui-input-block">
            <button class="layui-btn" button="button" lay-submit lay-filter="*">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>
@endsection
@section('scripts')
<script>
layui.use(['form', 'layer','upload'], function() {
    var form = layui.form, layer = layui.layer,upload = layui.upload;
    var $ = layui.$;
    //图片上传
    var uploadInst = upload.render({
        elem: '#upload_icon' //绑定元素
        ,url: '{{URL("admin/category_upload")}}' //上传接口
        ,done: function(res){
            //上传完毕回调
            if (res.type == "success"){
                $("#icon").val(res.msg.filename)
                $(".icon").show()
                $(".icon").attr("src",res.msg.filepath)
            } else{
                alert(res.msg)
            }
        }
        ,error: function(){
            //请求异常回调
        }
    });
    $('#add-icon').click(function(){
        layer.open({
            type: 2,
            area: ['90%', '80%'],
            fixed: false, //不固定
            maxmin: true,
            title:'添加',
            content: '/admin/menu_icon',
            // cancel: function(index, layero){
            //     //当点击‘确定’按钮的时候，获取弹出层返回的值
            //     getValue();
            // }
        });
        // layer_show('添加', '/admin/freight_area',700,500);

    });
    form.on('submit(*)', function(data){

        console.log('数据',data.field)
        $.ajax({
            type: 'post'
            ,dataType: 'json'
            ,url: "/admin/menu_edit"
            ,data: data.field
            ,success: function(result) {
                if(result.type == 'success') {
                    parent.createNode = result.msg.data;
                    layer.msg(result.msg.msg, {
                        icon:1
                        ,time: 1000
                        ,end: function() {
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index)
                        }
                    });
                } else {
                    layer.msg(result.msg, {icon:2});
                }
            }
            ,error: function(result) {
                    layer.msg('服务器发生错误，请稍后重试', {icon:2});
            }
        });
        return false;
    });
    $('img.icon').attr('src') != '' && $('img.icon').show();
});
</script>
@endsection
