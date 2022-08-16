<?php

namespace App\Http\Controllers\Admin;

use App\Models\Goods;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsController extends Controller
{
    public function lists(Request $request){
        $limit = $request->input('limit',10);
        $name = $request->input('name');
        $id = $request->input('id');
        $query = Goods::query();
        if($id){
            $query = $query->where('id',$id);
        }
        if($name){
            $query = $query->where('name','like','%'.$name.'%');
        }
        $query = $query->where('is_del',0)->orderBy('id','asc')->paginate($limit);
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    public function add(Request $request){
        $id = $request->input('id');
        if(empty($id)){
            $goods = new Goods();
        }else{
            $goods = Goods::query()->find($id);
        }
        return view('admin.goods.add',['goods'=>$goods]);
    }

    public function postAdd(Request $request){
        $id = $request->input('id');
        $name = $request->input('name');
        $image = $request->input('image');
        $price = $request->input('price');
        $cost_price = $request->input('cost_price');
        $sort = $request->input('sort');
        $is_book = $request->input('is_book');
        $book_time = $request->input('book_time');
        $content = $request->input('content');
        $contract_address = $request->input('contract_address');
        $reward_type = $request->input('reward_type');

        if(empty($name)) return $this->error('名称不能位空');
        if(empty($image)) return $this->error('请上传图片');
        if(empty($price)) return $this->error('价格不能为空');
        if(empty($cost_price)) return $this->error('回收价不能为空');
        if(empty($sort)) return $this->error('排序值不能为空');
        if($reward_type == null) return $this->error('请填写奖励类型');
        if($is_book == 1){
            if(empty($book_time)) return $this->error('请选择预售时间');
            if(strtotime($book_time) < time()) return $this->error('预售时间不能小于当前时间');
        }
        if(empty($content)) return $this->error('详情不能为空');
        if(empty($id)){
            $goods = new Goods();
            $goods->create_time = time();
        }else{
            $goods = Goods::query()->find($id);
        }
        $goods->name = $name;
        $goods->image = $image;
        $goods->price = $price;
        $goods->cost_price = $cost_price;
        $goods->sort = $sort;
        $goods->is_book = $is_book;
        $goods->book_time = strtotime($book_time);
        $goods->content = $content;
        $goods->contract_address = $contract_address;
        $goods->reward_type = $reward_type;
        try{
            $goods->save();
            return $this->success('操作成功');
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function del(Request $request){
        $id = trim($request->input('id'));
        if(empty($id)) return $this->error('参数错误');
        $goods = Goods::query()->find($id);
        try{
            $goods->is_del = 1;
            if($goods->save()){
                return $this->success('删除成功');
            }else{
                return $this->error('删除失败');
            }
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function search(Request $request){
        $get = $request->query();
        if($request->ajax()){
            $limit = $request->input('limit',10);
            $goods_id = $request->input('goods_id');
            $goods_name = $request->input('goods_name');
            $query = Goods::query();
            if($goods_id){
                $query = $query->where('id',$goods_id);
            }
            if($goods_name){
                $query = $query->where('name','like','%'.$goods_name.'%');
            }
            $query = $query->where('is_del',0)->orderBy('id','asc')->paginate($limit);
            return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
        }
        return view('admin.goods.search',['get'=>$get]);
    }

    public function three_search(Request $request){
        $get = $request->query();
        if($request->ajax()){
            $limit = $request->input('limit',10);
            $goods_id = $request->input('goods_id');
            $goods_name = $request->input('goods_name');
            $query = Goods::query();
            if($goods_id){
                $query = $query->where('id',$goods_id);
            }
            if($goods_name){
                $query = $query->where('name','like','%'.$goods_name.'%');
            }
            $query = $query->where('is_del',0)->orderBy('id','asc')->paginate($limit);
            return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
        }
        return view('admin.goods.three_search',['get'=>$get]);
    }

    public function egg_search(Request $request){
        $get = $request->query();
        if($request->ajax()){
            $limit = $request->input('limit',10);
            $goods_id = $request->input('goods_id');
            $goods_name = $request->input('goods_name');
            $query = Goods::query();
            if($goods_id){
                $query = $query->where('id',$goods_id);
            }
            if($goods_name){
                $query = $query->where('name','like','%'.$goods_name.'%');
            }
            $query = $query->where('is_del',0)->orderBy('id','asc')->paginate($limit);
            return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
        }
        return view('admin.goods.egg_search',['get'=>$get]);
    }
}
