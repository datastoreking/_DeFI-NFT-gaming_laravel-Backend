@extends('admin._layoutNew')
@section('page-head')

@endsection
@section('page-content')
    <form class="layui-form" method="POST">
        <div class="layui-form-item">
            <label class="layui-form-label">分类选择</label>
            <div class="layui-input-block">
                <select name="c_id" id="c_id">
                    <option value="0">请选择分类</option>
                    @foreach ($categoryList as $category)
                        <option @if(isset($box['c_id']) && $box['c_id'] == $category['id']){{'selected="selected"'}}@endif value="{{$category['id']}}">{{$category['name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-block">
                <input class="layui-input" name="name"  placeholder="请输入名称" type="text" value="{{$box['name']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">图片</label>
            <div class="layui-input-block">
                <button class="layui-btn" type="button" id="upload_img">选择图片</button>
                <br/>
                <img src="@if(!empty($box['image'])){{$box['image']}}@endif" class="image" style="display: @if(!empty($box['image'])){{"block"}}@else{{"none"}}@endif;max-width: 200px;height: auto;margin-top: 5px;">
                <input type="hidden" name="image" id="image" value="@if(!empty($box['image'])){{$box['image']}}@endif">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">封面图</label>
            <div class="layui-input-block">
                <button class="layui-btn" type="button" id="upload_cover">选择图片</button>
                <br/>
                <img src="@if(!empty($box['cover_image'])){{$box['cover_image']}}@endif" class="cover_image" style="display: @if(!empty($box['cover_image'])){{"block"}}@else{{"none"}}@endif;max-width: 200px;height: auto;margin-top: 5px;">
                <input type="hidden" name="cover_image" id="cover_image" value="@if(!empty($box['cover_image'])){{$box['cover_image']}}@endif">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">价格</label>
            <div class="layui-input-block">
                <input class="layui-input" name="price"  placeholder="请输入价格" type="text" value="{{$box['price']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序值</label>
            <div class="layui-input-block">
                <input class="layui-input" name="sort"  placeholder="请输入排序值" type="text" value="{{$box['sort']}}">
            </div>
        </div>
        <div class="layui-form-item layui-col-lg8 appoint_type appoint_type-3">
            <label class="layui-form-label">选择商品：</label>
            <div class="layui-input-block">
                <a class="layui-btn" id="search_goods">点击选择</a>
                <table class="layui-table">
                    <thead>
                    <tr>
                        <th>商品ID</th>
                        <th>商品名称</th>
                        <th>排序值</th>
                        <th>商品图片</th>
                        <th>售价</th>
                        <th>成本价</th>
                        <th>概率%</th>
                        <th>等级</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody id="goods_list">
                    @if(!empty($goods))
                        @foreach($goods as $value)
                            <tr>
                                <td class='ids'>{{$value->goods_id}}</td>
                                <td>{{$value->name}}<input type='hidden' name='goods_id[]' value='{{$value->goods_id}}'></td>
                                <td>{{$value->sort}}</td>
                                <td><img src='{{$value->image}}' style='width-max: 150px;height:50px;margin-left: 2px;'></td>
                                <td>{{$value->price}}</td>
                                <td>{{$value->cost_price}}</td>
                                <td><input type='text' style="width: 120px;" name='ratio[]' class="ratio" value='{{$value->ratio}}'></td>
                                <td><input type='text' style="width: 120px;" name='level[]' class="level" value='{{$value->level}}'></td>
                                <td><a onclick='sea_del(this)'>删除</a></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <input type="hidden" name="id" value="{{$box['id']}}">
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
                elem: '#upload_cover',
                url: '{{URL("api/upload")}}',
                multiple: 'false',
                done: function(res) {
                    if (res.code === 1) {
                        $("#cover_image").val(res.data.filename)
                        $(".cover_image").show()
                        $(".cover_image").attr("src", res.data.filepath)
                    } else {
                        alert(res.msg)
                    }
                },
                error: function() {
                    //请求异常回调
                }
            });
            form.on('submit(submit)', function (data) {
                var loading = layer.load(0, {
                    shade: [0.3, '#393D49'],
                });
                var data = data.field;
                $.ajax({
                    url:'/admin/box_three_add',
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
            $("#search_goods").on('click',function () {
                //获取已选中的盲盒id
                layer.open({
                    type: 2
                    ,id:"sea"
                    ,title:"选择商品"
                    ,offset: 't'
                    ,area: ['80%', '90%']
                    ,shade: 0
                    ,maxmin: false
                    ,content:"{{url('admin/goods_three_search')}}"
                });
            });

        });
        function sea_del($this){
            var _tr = $($this).parent().parent();
            $(_tr).remove()
        }
    </script>
@endsection
