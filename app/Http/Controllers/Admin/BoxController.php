<?php

namespace App\Http\Controllers\Admin;

use App\Models\Box;
use App\Models\BoxAward;
use App\Models\BoxLevel;
use App\Models\Egg;
use App\Models\Goods;
use App\Models\OrderGoods;
use App\Models\Prize;
use App\Models\Suit;
use App\Models\SuitGoods;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BoxController extends Controller
{
    public function one_lists(Request $request){
        $limit = $request->input('limit',10);
        $name = $request->input('name');
        $query = Box::query();
        if($name){
            $query = $query->where('name','like','%'.$name.'%');
        }
        $query = $query->where('type',1)->where('is_del',0)->orderBy('id','asc')->paginate($limit);
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    public function one_add(Request $request){
        $id = $request->input('id');
        $category = Category::query()->where('type',1)->where('is_del',0)->get();
        if(empty($id)){
            $box = new Box();
            $goods = [];
        }else{
            $box = Box::query()->find($id);
            $suit = Suit::query()->where('box_id',$id)->first();
            $goods = SuitGoods::query()->from('suit_goods as s')->leftJoin('goods as g','s.goods_id','=','g.id')
                ->select('s.goods_id','g.name','g.sort','g.image','g.price','g.cost_price','s.num as number','s.ratio','s.level')->where('s.suit_id',$suit->id)->get();
        }

        return view('admin.box.one_add',['box'=>$box,'categoryList'=>$category,'goods'=>$goods]);
    }

    public function one_postAdd(Request $request){
        $c_id = $request->input('c_id');
        $name = $request->input('name');
        $image = $request->input('image');
        $cover_image = $request->input('cover_image');
        $price = $request->input('price');
        $num = $request->input('num');
        $sale = $request->input('sale');
        $sort = $request->input('sort');
        $id = $request->input('id');
        $goods_id = $request->input('goods_id');
        $number = $request->input('number');
        $ratio = $request->input('ratio');
        $level = $request->input('level');

        if(empty($c_id)) return $this->error('请选择分类');
        if(empty($name)) return $this->error('名称不能为空');
        if(empty($image)) return $this->error('请上传图片');
        if(empty($cover_image)) return $this->error('请上传封面图片');
        if(empty($price)) return $this->error('价格不能为空');
        if(empty($num)) return $this->error('箱数不能为空');
        foreach ($ratio as $r){
            if(empty($r)){
                return $this->error('概率不能为空');
            }
        }
        foreach ($level as $l){
            if(empty($l)){
                return $this->error('级别不能为空');
            }
        }
        $total = 0;
        foreach ($number as $index=>$n){
            if(empty($n)){
                return $this->error('数量不能为空');
            }
            if(!in_array($level[$index],['Free','W','First','Last','War'])){
                $total += $n;
            }

        }
        if(empty($id)){
            if(empty($goods_id)) return $this->error('请选择赏品');
            if(empty($number)) return $this->error('赏品数量不能为空');
            if(empty($ratio)) return $this->error('赏品概率不能为空');
            if(empty($level)) return $this->error('商品级别不能为空');
            $box = new Box();
            $box->create_time = time();
        }else{
            $box = Box::query()->find($id);
        }
        $box->c_id = $c_id;
        $box->type = 1;
        $box->name = $name;
        $box->image = $image;
        $box->cover_image = $cover_image;
        $box->price = $price;
        $box->num = $num;
        $box->sale = $sale ?? 0;
        $box->sort = $sort;
        try{
            $box->save();
            if(empty($id)){
                $data = [];
                for ($i=0;$i<$num;$i++){
                    $keys = $i + 1;
                    $data[$i]['box_id'] = $box->id;
                    $data[$i]['is_end'] = 0;
                    $data[$i]['no_key'] = $keys;
                    $data[$i]['create_time'] = time();
                    $data[$i]['num'] = $total;
                    $data[$i]['surplus'] = $total;
                }
                DB::table('suit')->insert($data);
                $boxSuit = Suit::query()->where('box_id',$box->id)->get()->toArray();
                foreach ($goods_id as $k=>$v){
                    Goods::query()->where('id', $goods_id[$k])->where('reward_type',0)->update(['is_del'=>1]);
                    foreach ($boxSuit as $key=>$item){
                        $surplusNFTidArray = [];
                        for($i=1; $i<$number[$k]+1; $i++){
                            array_push($surplusNFTidArray, $i);
                        }
                        $boxSuitGoods = new SuitGoods();
                        $boxSuitGoods->suit_id = $item['id'];
                        $boxSuitGoods->goods_id = $goods_id[$k];
                        $boxSuitGoods->num = $number[$k];
                        $boxSuitGoods->ratio = $ratio[$k];
                        $boxSuitGoods->level = $level[$k];
                        $boxSuitGoods->surplus = $number[$k];
                        $boxSuitGoods->create_time = time();
                        $reward_type = Goods::query()->where('id', $goods_id[$k])->select('reward_type')->first()->reward_type;
                        if($reward_type == 0){
                            $boxSuitGoods->surplusNFTidArray = json_encode($surplusNFTidArray);
                        }
                        if(in_array($level[$k],['Free','W','First','Last','War'])){
                            $boxSuitGoods->is_special = 1;
                        }else{
                            $boxSuitGoods->is_special = 0;
                        }
                        $boxSuitGoods->save();
                    }
                }
            }
            return $this->success('操作成功');
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function two_lists(Request $request){
        $limit = $request->input('limit',10);
        $name = $request->input('name');
        $query = Box::query();
        if($name){
            $query = $query->where('name','like','%'.$name.'%');
        }
        $query = $query->where('type',2)->where('is_del',0)->orderBy('id','asc')->paginate($limit);
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    public function two_add(Request $request){
        $id = $request->input('id');
        $category = Category::query()->where('type',1)->where('is_del',0)->get();
        if(empty($id)){
            $box = new Box();
            $goods = [];
        }else{
            $box = Box::query()->find($id);
            $suit = Suit::query()->where('box_id',$id)->first();
            $goods = SuitGoods::query()->from('suit_goods as s')->leftJoin('goods as g','s.goods_id','=','g.id')
                ->select('s.goods_id','g.name','g.sort','g.image','g.price','g.cost_price','s.num as number','s.ratio','s.level')->where('s.suit_id',$suit->id)->get();
        }

        return view('admin.box.two_add',['box'=>$box,'categoryList'=>$category,'goods'=>$goods]);
    }

    public function two_postAdd(Request $request){
        $c_id = $request->input('c_id');
        $name = $request->input('name');
        $image = $request->input('image');
        $cover_image = $request->input('cover_image');
        $price = $request->input('price');
        $num = $request->input('num');
        $sale = $request->input('sale');
        $sort = $request->input('sort');
        $id = $request->input('id');
        $goods_id = $request->input('goods_id');
        $number = $request->input('number');
        $ratio = $request->input('ratio');
        $level = $request->input('level');

        if(empty($c_id)) return $this->error('请选择分类');
        if(empty($name)) return $this->error('名称不能为空');
        if(empty($image)) return $this->error('请上传图片');
        if(empty($cover_image)) return $this->error('请上传封面图片');
        if(empty($price)) return $this->error('价格不能为空');
        if(empty($num)) return $this->error('箱数不能为空');
        foreach ($ratio as $r){
            if(empty($r)){
                return $this->error('概率不能为空');
            }
        }
        foreach ($level as $l){
            if(empty($l)){
                return $this->error('级别不能为空');
            }
        }
        $total = 0;
        foreach ($number as $index=>$n){
            if(empty($n)){
                return $this->error('数量不能为空');
            }
            if(!in_array($level[$index],['Free','W','First','Last','War'])){
                $total += $n;
            }

        }
        if(empty($id)){
            if(empty($goods_id)) return $this->error('请选择赏品');
            if(empty($number)) return $this->error('赏品数量不能为空');
            if(empty($ratio)) return $this->error('赏品概率不能为空');
            if(empty($level)) return $this->error('商品级别不能为空');
            $box = new Box();
            $box->create_time = time();
        }else{
            $box = Box::query()->find($id);
        }
        $box->c_id = $c_id;
        $box->type = 2;
        $box->name = $name;
        $box->image = $image;
        $box->cover_image = $cover_image;
        $box->price = $price;
        $box->num = $num;
        $box->sale = $sale ?? 0;
        $box->sort = $sort;
        try{
            $box->save();
            if(empty($id)){
                $data = [];
                for ($i=0;$i<$num;$i++){
                    $keys = $i + 1;
                    $data[$i]['box_id'] = $box->id;
                    $data[$i]['is_end'] = 0;
                    $data[$i]['no_key'] = $keys;
                    $data[$i]['create_time'] = time();
                    $data[$i]['num'] = $total;
                    $data[$i]['surplus'] = $total;
                }
                DB::table('suit')->insert($data);
                $boxSuit = Suit::query()->where('box_id',$box->id)->get()->toArray();
                foreach ($goods_id as $k=>$v){
                    foreach ($boxSuit as $key=>$item){
                        $boxSuitGoods = new SuitGoods();
                        $boxSuitGoods->suit_id = $item['id'];
                        $boxSuitGoods->goods_id = $goods_id[$k];
                        $boxSuitGoods->num = $number[$k];
                        $boxSuitGoods->ratio = $ratio[$k];
                        $boxSuitGoods->level = $level[$k];
                        $boxSuitGoods->surplus = $number[$k];
                        $boxSuitGoods->create_time = time();
                        if(in_array($level[$k],['Free','W','First','Last','War'])){
                            $boxSuitGoods->is_special = 1;
                        }else{
                            $boxSuitGoods->is_special = 0;
                        }
                        $boxSuitGoods->save();
                    }
                }
            }
            return $this->success('操作成功');
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function three_lists(Request $request){
        $limit = $request->input('limit',10);
        $name = $request->input('name');
        $query = Box::query();
        if($name){
            $query = $query->where('name','like','%'.$name.'%');
        }
        $query = $query->where('type',3)->where('is_del',0)->orderBy('id','asc')->paginate($limit);
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    public function three_add(Request $request){
        $id = $request->input('id');
        $category = Category::query()->where('type',2)->where('is_del',0)->get();
        if(empty($id)){
            $box = new Box();
        }else{
            $box = Box::query()->find($id);
        }
        $goods = Prize::query()->from('prize as p')
            ->leftJoin('goods as g','p.goods_id','=','g.id')
            ->select('p.*','g.name','g.image','g.price','g.cost_price')
            ->where('p.box_id',$id)->orderBy('g.price','desc')->get();
        return view('admin.box.three_add',['box'=>$box,'categoryList'=>$category,'goods'=>$goods]);
    }

    public function three_postAdd(Request $request){
        $c_id = $request->input('c_id');
        $name = $request->input('name');
        $image = $request->input('image');
        $cover_image = $request->input('cover_image');
        $price = $request->input('price');
        $sort = $request->input('sort');
        $id = $request->input('id');
        $goods_id = $request->input('goods_id');
        $ratio = $request->input('ratio');
        $level = $request->input('level');

        if(empty($c_id)) return $this->error('请选择分类');
        if(empty($name)) return $this->error('名称不能为空');
        if(empty($image)) return $this->error('请上传图片');
        if(empty($cover_image)) return $this->error('请上传封面图片');
        if(empty($price)) return $this->error('价格不能为空');
        if(empty($goods_id)) return $this->error('请选择赏品');
        if(empty($ratio)) return $this->error('赏品概率不能为空');
        if(empty($level)) return $this->error('商品级别不能为空');
        foreach ($ratio as $r){
            if(empty($r)){
                return $this->error('概率不能为空');
            }
        }
        foreach ($level as $l){
            if(empty($l)){
                return $this->error('级别不能为空');
            }
        }
        if(empty($id)){

            $box = new Box();
            $box->create_time = time();
        }else{
            $box = Box::query()->find($id);
        }
        $box->c_id = $c_id;
        $box->type = 3;
        $box->name = $name;
        $box->image = $image;
        $box->cover_image = $cover_image;
        $box->price = $price;
        $box->sort = $sort;
        try{
            if($box->save()){
                Prize::query()->where('box_id',$box->id)->delete();
                foreach ($goods_id as $key=>$value){
                    $goods = Goods::query()->find($value);
                    $boxPrize = new Prize();
                    $boxPrize->box_id = $box->id;
                    $boxPrize->goods_id = $value;
                    $boxPrize->sort = $goods->sort;
                    $boxPrize->create_time = time();
                    $boxPrize->ratio = $ratio[$key];
                    $boxPrize->level = $level[$key];
                    $boxPrize->save();
                }
            }
            return $this->success('操作成功');
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function three_prize(Request $request){
        $box_id = $request->input('box_id');
        $log = BoxAward::query()->where('box_id',$box_id)->orderBy('id','desc')->get();
        return view('admin.box.three_prize',['box_id'=>$box_id,'logList'=>$log]);
    }

    public function three_postPrize(Request $request){
        $box_id = $request->input('box_id');
        $number = $request->input('number');
        if(empty($number)) return $this->error('编号不能为空');
        $orderGoods = OrderGoods::query()->where('box_id',$box_id)->orderBy('id','desc')->first();
        if($orderGoods && $number < $orderGoods->number) return $this->error('编号小于正常出赏编号');

        $award = new BoxAward();
        $award->box_id = $box_id;
        $award->number = $number;
        $award->is_award = 0;
        $award->create_time = time();
        if($award->save()){
            return $this->success('添加成功');
        }else{
            return $this->error('添加失败');
        }
    }

    public function egg_lists(Request $request){
        $limit = $request->input('limit',10);
        $name = $request->input('name');
        $query = Box::query();
        if($name){
            $query = $query->where('name','like','%'.$name.'%');
        }
        $query = $query->where('type',4)->where('is_del',0)->orderBy('id','asc')->paginate($limit);
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    public function egg_add(Request $request){
        $id = $request->input('id');
        $category = Category::query()->where('type',1)->where('is_del',0)->get();
        if(empty($id)){
            $box = new Box();
        }else{
            $box = Box::query()->find($id);
        }
        $goods = Egg::query()->from('egg as e')
            ->leftJoin('goods as g','e.goods_id','=','g.id')
            ->select('e.*','g.name','g.image','g.price','g.cost_price')
            ->where('e.box_id',$id)->orderBy('g.price','desc')->get();
        return view('admin.box.egg_add',['box'=>$box,'categoryList'=>$category,'goods'=>$goods]);
    }

    public function egg_postAdd(Request $request){
        $c_id = $request->input('c_id');
        $name = $request->input('name');
        $image = $request->input('image');
        $cover_image = $request->input('cover_image');
        $price = $request->input('price');
        $sort = $request->input('sort');
        $id = $request->input('id');
        $goods_id = $request->input('goods_id');
        $number = $request->input('number');
        $is_special = $request->input('is_special');
        $ratio = $request->input('ratio');

        if(empty($c_id)) return $this->error('请选择分类');
        if(empty($name)) return $this->error('名称不能为空');
        if(empty($image)) return $this->error('请上传图片');
        if(empty($cover_image)) return $this->error('请上传封面图片');
        if(empty($price)) return $this->error('价格不能为空');
        if(empty($goods_id)) return $this->error('请选择赏品');
        if(empty($ratio)) return $this->error('赏品概率不能为空');
        if(empty($number)) return $this->error('赏品数量不能为空');
        foreach ($ratio as $r){
            if(empty($r)){
                return $this->error('概率不能为空');
            }
        }
        foreach ($number as $n){
            if(empty($n)){
                return $this->error('数量不能为空');
            }
        }
        if(empty($id)){

            $box = new Box();
            $box->create_time = time();
        }else{
            $box = Box::query()->find($id);
        }
        $box->c_id = $c_id;
        $box->type = 4;
        $box->name = $name;
        $box->image = $image;
        $box->cover_image = $cover_image;
        $box->price = $price;
        $box->sort = $sort;
        try{
            $box->save();
            if(empty($id)){
                Egg::query()->where('box_id',$box->id)->delete();
                foreach ($goods_id as $key=>$value){
                    $goods = Goods::query()->find($value);
                    $boxEgg = new Egg();
                    $boxEgg->box_id = $box->id;
                    $boxEgg->goods_id = $value;
                    $boxEgg->sort = $goods->sort;
                    $boxEgg->create_time = time();
                    $boxEgg->ratio = $ratio[$key];
                    $boxEgg->number = $number[$key];
                    $boxEgg->surplus = $number[$key];
                    $boxEgg->is_special = $is_special[$key] ?? 0;
                    $boxEgg->save();
                }
            }
            return $this->success('操作成功');
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function state(Request $request){
        $id = trim($request->input('id'));
        if(empty($id)) return $this->error('参数错误');
        $box = Box::query()->find($id);
        if($box->state == 1){
            $box->state = 2;
        }else{
            $box->state = 1;
        }
        if($box->save()){
            return $this->success('操作成功');
        }else{
            return $this->error('操作失败');
        }
    }

    public function del(Request $request){
        $id = trim($request->input('id'));
        if(empty($id)) return $this->error('参数错误');
        $box = Box::query()->find($id);
        try{
            $box->is_del = 1;
            if($box->save()){
                return $this->success('删除成功');
            }else{
                return $this->error('删除失败');
            }
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function level_index(Request $request){
        $box_id = $request->input('box_id');
        return view('admin.box.level_index',['box_id'=>$box_id]);
    }

    public function level_lists(Request $request){
        $limit = $request->input('limit',10);
        $box_id = $request->input('box_id');
        $query = BoxLevel::query();
        $query = $query->where('box_id',$box_id)->orderBy('id','asc')->paginate($limit);
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    public function level_add(Request $request){
        $id = $request->input('id');
        $box_id = $request->input('box_id');
        if(empty($id)){
            $boxLevel = new BoxLevel();
        }else{
            $boxLevel = BoxLevel::query()->find($id);
        }
        return view('admin.box.level_add',['boxLevel'=>$boxLevel,'box_id'=>$box_id]);
    }

    public function level_postAdd(Request $request){
        $level = $request->input('level');
        $ratio = $request->input('ratio');
        $box_id = $request->input('box_id');
        $id = $request->input('id');
        if(empty($level)) return $this->error('等级不能为空');
        if(empty($ratio)) return $this->error('概率不能为空');
        if(empty($id)){
            $boxLevel = new BoxLevel();
            $boxLevel->create_time = time();
        }else{
            $boxLevel = BoxLevel::query()->find($id);
        }
        $boxLevel->level = $level;
        $boxLevel->ratio = $ratio;
        $boxLevel->box_id = $box_id;
        try{
            $boxLevel->save();
            return $this->success('操作成功');
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function level_del(Request $request){
        $id = trim($request->input('id'));
        if(empty($id)) return $this->error('参数错误');
        $level = BoxLevel::query()->find($id);
        try{
            $res = $level->delete();
            if($res){
                return $this->success('删除成功');
            }else{
                return $this->error('删除失败');
            }
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }
}
