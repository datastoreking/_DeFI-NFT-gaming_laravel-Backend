<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;

use App\Models\Ad;
use App\Models\Box;
use App\Models\BoxLevel;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Config;
use App\Models\Egg;
use App\Models\Goods;
use App\Models\Level;
use App\Models\Prize;
use App\Models\Suit;
use App\Models\SuitGoods;
use App\Models\Order;
use App\Models\OrderGoods;
use App\Models\Recharge;
use App\Models\User;
use App\Models\UserCoupon;
use App\Services\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function ad(Request $request){
        $type = $request->input('type',1);
        $ad = Ad::query()->where('type',$type)->select('image','url')->orderBy('id','asc')->get();
        return $this->ajax(1,'请求成功',$ad);
    }

    //盲盒类型
    public function category(Request $request){
        $type = $request->input('type',1);
        $category = Category::query()->where('type',$type)->where('is_del',0)->select('id','name')->get()->toArray();
        if($type == 2){
            array_unshift($category,['id'=>0,'name'=>'推荐']);
        }
        return $this->ajax(1,'请求成功',$category);
    }

    //盲盒等级
    public function level(){
        $level = Level::query()->select('name','level')->orderBy('sort','asc')->get();
        return $this->ajax(1,'请求成功',$level);
    }

    //盲盒列表
    public function bidAdd(Request $request) {
        $box_id = $request->input('box_id');
        $bid_count_add = $request->input('bid_count_add');
        $query = Box::query()->select('bid_count')->where('id', $box_id)->first();
        $currentBidcount = $query->bid_count;
        $updatedBidcount = $currentBidcount + $bid_count_add;
        $getCID = Box::query()->select('c_id')->where('id', $box_id)->first()->c_id;
        if($getCID == 2){
            Box::where('id', $box_id)->update(['bid_count' => $updatedBidcount]);
        }
        $queryUpdated = Box::query()->select('id', 'bid_count')->where('id', $box_id)->first();
        return($queryUpdated);
    }

    //盲盒列表 - NewAPI2
    public function getBlindBoxesList(Request $request){
        $page = $request->input('page',1);
        $limit = $request->input('pageSize',2);
        $c_id = $request->input('blindBoxesType');

        $query = Box::query()->select('id', 'name','image','cover_image','price', 'create_time')
            ->where('state',1)->where('is_del',0)->where('c_id',$c_id);
        $query = $query->whereIn('type',[1])->orderBy('sort','asc')->paginate($limit);
        foreach ($query as &$item){
            $item->blindBoxesType = $c_id;
            if($c_id == 2){
                $item->surplus = 10 - Box::query()->select('bid_count')->where('id', $item->id)->first()->bid_count;
                $item->totalNumber = 10;
            }
        }
        return $this->ajax(0,'请求成功',[
            'count'=>$query->total(),
            'pageNumber'=>$page,
            'pages'=>$query->lastPage(),
            'data'=>$query->items(),
        ]);
    }

    //通过用户 ID 获取框详细信息 - NewAPI3
    public function getBlindBoxe(Request $request){
        $box_id = $request->input('box_id');
        $query = Box::query()->select('id', 'name','image','cover_image','price','create_time', 'c_id')
            ->where('state',1)->where('is_del',0)->where('id',$box_id);
        if($query->first()->c_id == 2){
            $surplus = 10 - Box::query()->select('bid_count')->where('id', $box_id)->first()->bid_count;
            $totalNumber = 10;
        }else{
            $surplus=null;
            $total_number=null;
        }
        return $this->ajax(0,'请求成功',[
            'data'=>[
                'id'=>$query->first()->id,
                'price'=>$query->first()->id,
                'image'=>$query->first()->image,
                'cover_image'=>$query->first()->cover_image,
                'name'=>$query->first()->name,
                'create_time'=>$query->first()->create_time,
                'blindBoxesType'=>$query->first()->c_id,
                'surplus'=>$surplus,
                'total_number'=>$totalNumber,
            ],
        ]);
    }

    //根据盲盒的id，获取该盲盒内包含的所有的nft - NewAPI4
    public function blindBoxeDetailList(Request $request){
        $id = $request->input('id');
        $suit_id = $request->input('suit_id');
        $uid = auth()->guard('api')->id();
        if(empty($id)) return $this->ajax(0,'参数错误');
        $box = Box::query()->where('id',$id)->select('id','name','image','cover_image','price','create_time','type')->first();
        if($suit_id){
            $suit = Suit::query()->where('box_id',$id)->where('id',$suit_id)->first();
        }else{
            $suit = Suit::query()->where('box_id',$id)->where('is_end',0)->orderBy('id','asc')->first();
            if(!$suit){
                $suit = Suit::query()->where('box_id',$id)->orderBy('id','asc')->first();
            }
        }
        $box->surplus = $suit->surplus;
        $box->totalNumber = $suit->num;
        $goods = DB::table('suit_goods as s')->leftJoin('goods as g','s.goods_id','=','g.id')
            ->select('s.num','s.surplus','s.level','s.is_special','g.name','g.image','g.price','g.is_book', 'g.content')->where('s.suit_id',$suit->id)->get()->each(function($item) use ($suit){
                $item->level_name = DB::table('level')->where('level',$item->level)->value('name');
                if($item->is_special == 0 && $suit->surplus > 0){
                    $ratio = bcdiv($item->surplus,$suit->surplus,5);
                    $item->ratio = sprintf('%.2f',bcmul($ratio,100,3));
                }else{
                    $item->ratio = 0;
                }
            });
        $box->goods = $goods;
        // $box->level = $goods->level;
        // $box->description = $goods->content;
        return $this->ajax(1,'请求成功',$box);
    }

    //盲盒列表
    public function boxList(Request $request){
        $page = $request->input('page',1);
        $limit = $request->input('limit',2);
        $c_id = $request->input('c_id',1);
        $name = $request->input('name');

        $query = Box::query()->select('id','name','image','cover_image','price','type','create_time')
            ->where('state',1)->where('is_del',0)->where('c_id',$c_id);
        if($name){
            $query = $query->where('name','like','%'.$name.'%');
        }
        $query = $query->whereIn('type',[1,2])->orderBy('sort','asc')->paginate($limit);
        foreach ($query as &$item){
            $item->total = Suit::query()->where('box_id',$item->id)->count();
            $item->surplus = Suit::query()->where('box_id',$item->id)->where('is_end',0)->count();
        }
        return $this->ajax(1,'请求成功',[
            'count'=>$query->total(),
            'page'=>$page,
            'pages'=>$query->lastPage(),
            'data'=>$query->items(),
        ]);
    }

    //盲盒详情
    public function boxDetail(Request $request){
        $id = $request->input('id');
        $suit_id = $request->input('suit_id');
        $uid = auth()->guard('api')->id();
        if(empty($id)) return $this->ajax(0,'参数错误');
        $box = Box::query()->where('id',$id)->select('id','name','image','cover_image','price','create_time','sale','type')->first();
        if($suit_id){
            $suit = Suit::query()->where('box_id',$id)->where('id',$suit_id)->first();
        }else{
            $suit = Suit::query()->where('box_id',$id)->where('is_end',0)->orderBy('id','asc')->first();
            if(!$suit){
                $suit = Suit::query()->where('box_id',$id)->orderBy('id','asc')->first();
            }
        }

        $box->suit_id = $suit->id;
        $box->is_free = 0;
        $box->free_first = 0;
        $box->free_end = 0;
        $free = SuitGoods::query()->where(['suit_id'=>$suit->id,'level'=>'Free'])->first();
        if($free){
            $box->is_free = 1;
            $box->free_first = 1;
            $box->free_end = $suit->num * Config::getValue('box_free');
        }
        $box->is_w = 0;
        $box->w_first = 0;
        $box->w_end = 0;
        $w = SuitGoods::query()->where(['suit_id'=>$suit->id,'level'=>'W'])->first();
        if($w){
            $box->is_w = 1;
            $box->w_first = 1;
            $box->w_end = $suit->num;
        }
        $box->is_first = 0;
        $box->first_first = 0;
        $box->first_end = 0;
        $first = SuitGoods::query()->where(['suit_id'=>$suit->id,'level'=>'First'])->first();
        if($first){
            $box->is_first = 1;
            $box->first_first = 1;
            $box->first_end = $suit->num * 0.5;
        }

        $box->is_last = 0;
        $box->last_first = 0;
        $box->last_end = 0;
        $last = SuitGoods::query()->where(['suit_id'=>$suit->id,'level'=>'Last'])->first();
        if($last){
            $box->is_last = 1;
            $box->last_first = $suit->num * 0.5 + 1;
            $box->last_end = $suit->num;
        }

        $box->is_war = 0;
        $box->war_first = 0;
        $box->war_end = 0;
        $war = SuitGoods::query()->where(['suit_id'=>$suit->id,'level'=>'War'])->first();
        if($war){
            $box->is_war = 1;
            $box->war_first = 1;
            $box->war_end = $suit->num;
        }

        $box->total_suit = Suit::query()->where('box_id',$id)->count();
        $box->current_suit = $suit->no_key;

        $box->is_collection = 0;
        $collection = Collection::query()->where(['uid'=>$uid,'box_id'=>$id])->first();
        if($collection){
            $box->is_collection = 1;
        }
        //已购买数量
        $buy_num = OrderGoods::query()->where('uid',$uid)->where('box_id',$id)->where('suit_id',$suit->id)->count();
        $box->buy_num = $buy_num;
        $box->total_num = $suit->num;
        $box->total_surplus = $suit->surplus;
        $goods = DB::table('suit_goods as s')->leftJoin('goods as g','s.goods_id','=','g.id')
            ->select('s.num','s.surplus','s.level','s.is_special','g.name','g.image','g.price','g.is_book')->where('s.suit_id',$suit->id)->get()->each(function($item) use ($suit){
                $item->level_name = DB::table('level')->where('level',$item->level)->value('name');
                if($item->is_special == 0 && $suit->surplus > 0){
                    $ratio = bcdiv($item->surplus,$suit->surplus,5);
                    $item->ratio = sprintf('%.2f',bcmul($ratio,100,3));
                }else{
                    $item->ratio = 0;
                }
            });
        $box->goods = $goods;
        return $this->ajax(1,'请求成功',$box);
    }

    //当前箱子余量
    public function suitNum(Request $request){
        $suit_id = $request->input('suit_id');
        if(empty($suit_id)) return $this->ajax(0,'参数错误');
        $total = DB::table('suit')->where('id',$suit_id)->value('surplus');
        $goods = DB::table('suit_goods as s')->leftJoin('goods as g','s.goods_id','=','g.id')
            ->select('s.num','s.surplus','s.level','s.is_special','g.name')->where('s.suit_id',$suit_id)->get()->each(function($item) use ($total){
                $item->level_name = DB::table('level')->where('level',$item->level)->value('name');
                if($item->is_special == 0 && $total > 0){
                    $ratio = bcdiv($item->surplus,$total,5);
                    $item->ratio = sprintf('%.2f',bcmul($ratio,100,3));
                }else{
                    $item->ratio = 0;
                }
            });
        return $this->ajax(1,'请求成功',$goods);
    }
    //NewAPI6
    public function blindBoxeRecordList(Request $request){
        $box_id = $request->input('box_id');
        $suit_id = $request->input('suit_id');
        $page = $request->input('page',1);
        $limit = $request->input('pageSize',6);
        $number = $request->input('number');
        $query = OrderGoods::query()->from('order_goods as o')
            ->leftJoin('user as u','o.uid','=','u.id')
            ->select('u.id','u.nickname','u.avatar','o.level','o.level_name','o.is_special','o.name','o.create_time')
            ->where('o.box_id',$box_id)->where('o.suit_id',$suit_id);
        if($number){
            $query = $query->where('o.number','<=',$number);
        }
        $query = $query->orderBy('o.number','desc')->paginate($limit);
        $suit = Suit::query()->find($suit_id);
        if(!$number){
            if($suit->is_end == 1){
                $number = OrderGoods::query()->where('box_id',$box_id)->where('suit_id',$suit_id)->orderBy('number','desc')->value('number');
            }else{
                $number = OrderGoods::query()->where('box_id',$box_id)->where('suit_id',$suit_id)->where('is_special',0)->orderBy('number','desc')->value('number');
            }
        }
        $result = array();
        for($i = 0; $i < count($query->items()); $i ++){
            $result[$i]['id'] = $query->items()[$i]['id'];
            $result[$i]['name'] = $query->items()[$i]['nickname'];
            $result[$i]['img'] = $query->items()[$i]['avatar'];
            $result[$i]['nftName'] = $query->items()[$i]['name'];
            $result[$i]['level'] = $query->items()[$i]['level'];
            $result[$i]['time'] = $query->items()[$i]['create_time'];
        }
        return $this->ajax(1,'请求成功', $result);
    }

    //NewAPI7

    public function getChangeBoxList(Request $request){
        $id = $request->input('id');
        $suit = Suit::query()->where('box_id',$id)->where('is_end',0)->orderBy('id','asc')->first();
        $goods = DB::table('suit_goods as s')->leftJoin('goods as g','s.goods_id','=','g.id')
            ->select('s.num','s.surplus','s.level','g.name', 'g.id')->where('s.suit_id',$suit->id);
        $surplus = 0;
        $result = array();
        for($i = 0; $i < count($goods->get()); $i ++){
            $result[$i]['id'] = $goods->get()[$i]->id;
            $result[$i]['name'] = $goods->get()[$i]->name;
            $result[$i]['surplus'] = $goods->get()[$i]->surplus;
            $surplus += $result[$i]['surplus'];
            $result[$i]['total'] = $goods->get()[$i]->num;
        }
        $data = [
            'id' => $id,
            'surplus' => $surplus,
            'nftList' => $result
        ];
        return $this->ajax(1,'请求成功',$data);
    }

    //NewAPI8
    public function buyNFT(Request $request) {
        $id = $request->input('id');
        $buyNumber = $request->input('buyNumber');
        $suit = Suit::query()->where('box_id',$id)->where('is_end',0)->orderBy('id','asc')->first();
        $goods = DB::table('suit_goods as s')->select('s.num','s.surplus', 's.sales', 's.ratio','s.goods_id')->where('s.suit_id',$suit->id);
        $calcAmount = 0;
        switch($buyNumber){
            case 1:
                // get goodsId to remove according to random number
                $randomNumber = rand(1,100000);
                for($i = 0; $i < count($goods->get()); $i ++){
                    $calcAmount += $goods->get()[$i]->ratio * 1000;
                    if($randomNumber > $calcAmount) continue;
                    else{
                        $getGoodsId = $goods->get()[$i]->goods_id;
                        var_dump($getGoodsId);
                        $getSurplus = $goods->get()[$i]->surplus - 1;
                        var_dump($getSurplus);
                        $getSales = $goods->get()[$i]->sales + 1;
                        break;
                    }
                }

                //remove that item in box
                DB::table('suit_goods')
                            ->where('goods_id', $getGoodsId)
                            ->update(['sales' => $getSales, 'surplus' => $getSurplus]);
                if($getSurplus == 0){   
                    DB::table('box')
                        ->where('id', $id)
                        ->update(['is_del' => 1]);
                } 
                return(Box::query()->select('is_del')->where('id', $id)->first());
                //return coin if that item is an special
                
                // $accessToken = $request->input('accessToken'); 
                // $params=['accessToken'=>$accessToken];
                // $ch = curl_init('https://app.gamifly.co:3001/auth/login');
                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                // curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                // $response = curl_exec($ch);
                // curl_close($ch);
                // return($response);
                break;
            default: break;
        }
    }

    //当前箱子记录
    public function boxGoods(Request $request){
        $box_id = $request->input('box_id');
        $suit_id = $request->input('suit_id');
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        $number = $request->input('number');
        $query = OrderGoods::query()->from('order_goods as o')
            ->leftJoin('user as u','o.uid','=','u.id')
            ->select('u.nickname','u.avatar','o.level','o.level_name','o.is_special','o.number','o.name','o.create_time')
            ->where('o.box_id',$box_id)->where('o.suit_id',$suit_id);
        if($number){
            $query = $query->where('o.number','<=',$number);
        }
        $query = $query->orderBy('o.number','desc')->paginate($limit);
        $suit = Suit::query()->find($suit_id);
        if(!$number){
            if($suit->is_end == 1){
                $number = OrderGoods::query()->where('box_id',$box_id)->where('suit_id',$suit_id)->orderBy('number','desc')->value('number');
            }else{
                $number = OrderGoods::query()->where('box_id',$box_id)->where('suit_id',$suit_id)->where('is_special',0)->orderBy('number','desc')->value('number');
            }
        }
        return $this->ajax(1,'请求成功',[
            'number'=>$number,
            'count'=>$query->total(),
            'page'=>$page,
            'pages'=>$query->lastPage(),
            'data'=>$query->items(),
        ]);
    }

    //竞技赏记录
    public function boxGoodsLog(Request $request){
        $box_id = $request->input('box_id');
        $suit_id = $request->input('suit_id');
        $suit = Suit::query()->find($suit_id);
        if($suit->is_end == 1){
            $query = OrderGoods::query()->from('order_goods as o')
                ->leftJoin('user as u','o.uid','=','u.id')
                ->select('u.nickname','u.avatar','o.level','o.level_name','o.is_special','o.number','o.name','o.create_time')
                ->where('o.box_id',$box_id)->where('o.suit_id',$suit_id)
                ->orderBy('o.number','desc')->get();
        }else{
            $query = [];
            for ($i = $suit->num;$i > 0;$i--){
                $res = OrderGoods::query()->from('order_goods as o')
                    ->leftJoin('user as u','o.uid','=','u.id')
                    ->select('u.nickname','u.avatar','o.level','o.level_name','o.is_special','o.number','o.name','o.create_time')
                    ->where('o.box_id',$box_id)->where('o.suit_id',$suit_id)->where('o.number',$i)->first();
                if($res){
                    array_push($query,$res);
                }else{
                    $res['number'] = $i;
                    array_push($query,$res);
                }
            }
        }
        return $this->ajax(1,'请求成功',$query);
    }

    //次数排行
    // public function timesRank(Request $request){
    //     $box_id = $request->input('box_id');
    //     $suit_id = $request->input('suit_id');
    //     $where['box_id'] = $box_id;
    //     $where['suit_id'] = $suit_id;
    //     $rank = OrderGoods::query()->where($where)->select('uid')->distinct()->get()->toArray();
    //     foreach ($rank as $key=>$value){
    //         $user = User::query()->find($value['uid']);
    //         $rank[$key]['nickname'] = $user->nickname;
    //         $rank[$key]['avatar'] = $user->avatar;
    //         $rank[$key]['total'] = OrderGoods::query()->where($where)->where('is_special',0)->where('uid',$value['uid'])->count();
    //         $levelData = OrderGoods::query()->where($where)->where('uid',$value['uid'])->select('level')->distinct('level')->get()->toArray();
    //         foreach ($levelData as $k=>$v){
    //             $times = OrderGoods::query()->where($where)->where('uid',$value['uid'])->where('level',$v['level'])->count();
    //             $level = Level::query()->where('level',$v['level'])->first();
    //             $levelData[$k]['times'] = $times;
    //             $levelData[$k]['sort'] = $level->sort;
    //             $levelData[$k]['level_name'] = $level->name;
    //         }
    //         $sorts = array_column($levelData,'sort');
    //         array_multisort($sorts, SORT_ASC, $levelData);
    //         $rank[$key]['times'] = $levelData;
    //     }
    //     $sort = array_column($rank,'total');
    //     array_multisort($sort, SORT_DESC, $rank);
    //     return $this->ajax(1,'请求成功',$rank);
    // }

    //竞技赏次数
    // public function timesRankLog(Request $request){
    //     $box_id = $request->input('box_id');
    //     $suit_id = $request->input('suit_id');
    //     $where['box_id'] = $box_id;
    //     $where['suit_id'] = $suit_id;
    //     $suit = Suit::query()->find($suit_id);
    //     $rank = OrderGoods::query()->where($where)->select('uid')->distinct()->get()->toArray();
    //     foreach ($rank as $key=>$value){
    //         $user = User::query()->find($value['uid']);
    //         $rank[$key]['nickname'] = $user->nickname;
    //         $rank[$key]['avatar'] = $user->avatar;
    //         $rank[$key]['total'] = OrderGoods::query()->where($where)->where('is_special',0)->where('uid',$value['uid'])->count();
    //         if($suit->is_end == 1){
    //             $levelData = OrderGoods::query()->where($where)->where('uid',$value['uid'])->select('level')->distinct('level')->get()->toArray();
    //         }else{
    //             $levelData = OrderGoods::query()->where($where)->where('uid',$value['uid'])->where('is_special',0)->select('level')->distinct('level')->get()->toArray();
    //         }

    //         foreach ($levelData as $k=>$v){
    //             $times = OrderGoods::query()->where($where)->where('uid',$value['uid'])->where('level',$v['level'])->count();
    //             $level = Level::query()->where('level',$v['level'])->first();
    //             $levelData[$k]['times'] = $times;
    //             $levelData[$k]['sort'] = $level->sort;
    //             $levelData[$k]['level_name'] = $level->name;
    //         }
    //         $sorts = array_column($levelData,'sort');
    //         array_multisort($sorts, SORT_ASC, $levelData);
    //         $rank[$key]['times'] = $levelData;
    //     }
    //     $sort = array_column($rank,'total');
    //     array_multisort($sort, SORT_DESC, $rank);
    //     return $this->ajax(1,'请求成功',$rank);
    // }
    
    //NewAPI5
    public function blindBoxeRankingList(Request $request){
        $pageNumber = $request->input('page',1);
        $pageSize = $request->input('pageSize',2);
        $box_id = $request->input('box_id');
        //$suit_id = $request->input('suit_id');
        $where['box_id'] = $box_id;
        //$where['suit_id'] = $suit_id;
        //$rank_page = OrderGoods::query()->where($where)->pagination($pageSize);
        $rank = OrderGoods::query()->where($where)->select('uid')->distinct()->get()->toArray();
        $count = count($rank);
        $box = Box::query()->find($box_id);
        foreach ($rank as $key=>$value){
            $user = User::query()->find($value['uid']);
            $rank[$key]['name'] = $user->nickname;
            $rank[$key]['img'] = $user->avatar;
            $rank[$key]['total'] = OrderGoods::query()->where($where)->where('is_special',0)->where('uid',$value['uid'])->count();
            $total_price = 0;
            $orderGoods = OrderGoods::query()->where($where)->where('uid',$value['uid'])->get();
            foreach ($orderGoods as $item){
                $total_price += $item->cost_price - $box->price;
            }
            $rank[$key]['total_price'] = $total_price;
            $levelData = OrderGoods::query()->where($where)->where('uid',$value['uid'])->select('level')->distinct('level')->get()->toArray();
            foreach ($levelData as $k=>$v){
                $times = OrderGoods::query()->where($where)->where('uid',$value['uid'])->where('level',$v['level'])->count();
                $level = Level::query()->where('level',$v['level'])->first();
                $levelData[$k]['time'] = $times;
                $levelData[$k]['rank'] = $level->sort;
                //$levelData[$k]['level_name'] = $level->name;
            }
            $sorts = array_column($levelData,'rank');
            array_multisort($sorts, SORT_ASC, $levelData);
            $rank[$key]['times'] = $levelData;
        }
        $sort = array_column($rank,'total_price');
        array_multisort($sort, SORT_DESC, $rank);
        
        return $this->ajax(1,'请求成功',$rank);
    }

    //欧皇排行
    // public function luckRank(Request $request){
    //     $box_id = $request->input('box_id');
    //     $suit_id = $request->input('suit_id');
    //     $where['box_id'] = $box_id;
    //     $where['suit_id'] = $suit_id;
    //     $rank = OrderGoods::query()->where($where)->select('uid')->distinct()->get()->toArray();
    //     $box = Box::query()->find($box_id);
    //     foreach ($rank as $key=>$value){
    //         $user = User::query()->find($value['uid']);
    //         $rank[$key]['nickname'] = $user->nickname;
    //         $rank[$key]['avatar'] = $user->avatar;
    //         $rank[$key]['total'] = OrderGoods::query()->where($where)->where('is_special',0)->where('uid',$value['uid'])->count();
    //         $total_price = 0;
    //         $orderGoods = OrderGoods::query()->where($where)->where('uid',$value['uid'])->get();
    //         foreach ($orderGoods as $item){
    //             $total_price += $item->cost_price - $box->price;
    //         }
    //         $rank[$key]['total_price'] = $total_price;
    //         $levelData = OrderGoods::query()->where($where)->where('uid',$value['uid'])->select('level')->distinct('level')->get()->toArray();
    //         foreach ($levelData as $k=>$v){
    //             $times = OrderGoods::query()->where($where)->where('uid',$value['uid'])->where('level',$v['level'])->count();
    //             $level = Level::query()->where('level',$v['level'])->first();
    //             $levelData[$k]['times'] = $times;
    //             $levelData[$k]['sort'] = $level->sort;
    //             $levelData[$k]['level_name'] = $level->name;
    //         }
    //         $sorts = array_column($levelData,'sort');
    //         array_multisort($sorts, SORT_ASC, $levelData);
    //         $rank[$key]['times'] = $levelData;
    //     }
    //     $sort = array_column($rank,'total_price');
    //     array_multisort($sort, SORT_DESC, $rank);
    //     return $this->ajax(1,'请求成功',$rank);
    // }

    //竞技场欧皇
    // public function luckRankLog(Request $request){
    //     $box_id = $request->input('box_id');
    //     $suit_id = $request->input('suit_id');
    //     $where['box_id'] = $box_id;
    //     $where['suit_id'] = $suit_id;
    //     $suit = Suit::query()->find($suit_id);
    //     $rank = OrderGoods::query()->where($where)->select('uid')->distinct()->get()->toArray();
    //     $box = Box::query()->find($box_id);
    //     foreach ($rank as $key=>$value){
    //         $user = User::query()->find($value['uid']);
    //         $rank[$key]['nickname'] = $user->nickname;
    //         $rank[$key]['avatar'] = $user->avatar;
    //         $rank[$key]['total'] = OrderGoods::query()->where($where)->where('is_special',0)->where('uid',$value['uid'])->count();
    //         $total_price = 0;
    //         if($suit->is_end == 1){
    //             $orderGoods = OrderGoods::query()->where($where)->where('uid',$value['uid'])->get();
    //             $levelData = OrderGoods::query()->where($where)->where('uid',$value['uid'])->select('level')->distinct('level')->get()->toArray();
    //         }else{
    //             $orderGoods = OrderGoods::query()->where($where)->where('is_special',0)->where('uid',$value['uid'])->get();
    //             $levelData = OrderGoods::query()->where($where)->where('uid',$value['uid'])->where('is_special',0)->select('level')->distinct('level')->get()->toArray();
    //         }

    //         foreach ($orderGoods as $item){
    //             $total_price += $item->cost_price - $box->price;
    //         }
    //         $rank[$key]['total_price'] = $total_price;
    //         foreach ($levelData as $k=>$v){
    //             $times = OrderGoods::query()->where($where)->where('uid',$value['uid'])->where('level',$v['level'])->count();
    //             $level = Level::query()->where('level',$v['level'])->first();
    //             $levelData[$k]['times'] = $times;
    //             $levelData[$k]['sort'] = $level->sort;
    //             $levelData[$k]['level_name'] = $level->name;
    //         }
    //         $sorts = array_column($levelData,'sort');
    //         array_multisort($sorts, SORT_ASC, $levelData);
    //         $rank[$key]['times'] = $levelData;
    //     }
    //     $sort = array_column($rank,'total_price');
    //     array_multisort($sort, SORT_DESC, $rank);
    //     return $this->ajax(1,'请求成功',$rank);
    // }


    

    //换箱-箱子列表
    public function suit(Request $request){
        $id = $request->input('id');
        if(empty($id)) return $this->ajax(0,'参数错误');
        $suitList = Suit::query()->select('id','is_end','no_key','surplus')->where('box_id',$id)->get()->toArray();
        $data = Common::arrSplit($suitList,10);
        $result = [];
        foreach ($data as $key=>$value){
            $first = reset($value);
            $end = end($value);
            $result[$key]['first_key'] = $first['no_key'];
            $result[$key]['end_key'] = $end['no_key'];
            $result[$key]['first_id'] = $first['id'];
            $result[$key]['end_id'] = $end['id'];
        }
        foreach ($result as $k=>$v){
            $suit = Suit::query()->where('box_id',$id)->whereBetween('id',[$v['first_id'],$v['end_id']])->select('id','no_key','is_end','surplus')->get()->each(function ($item){
                $goods = SuitGoods::query()->where('suit_id',$item->id)->select('num','surplus','level')->get();
                foreach ($goods as &$map){
                    $map->level_name = Level::query()->where('level',$map->level)->value('name');
                }
                $item->goods = $goods;
            });
            $result[$k]['suit'] = $suit;
        }
        return $this->ajax(1,'请求成功',$result);
    }

    //换箱子-选择箱子
    public function choiceSuit(Request $request){
        $id = $request->input('id');
        $uid = auth()->guard('api')->id();
        if(empty($id)) return $this->ajax(0,'参数错误');
        $suit = Suit::query()->where('id',$id)->first();
        //已购买数量
        $buy_num = OrderGoods::query()->where('uid',$uid)->where('box_id',$suit->box_id)->where('suit_id',$id)->count();
        $data['suit_id'] = $suit->id;
        $data['current_suit'] = $suit->no_key;
        $data['total_num'] = $suit->num;
        $data['total_surplus'] = $suit->surplus;
        $data['buy_num'] = $buy_num;
        $goods = DB::table('suit_goods as s')->leftJoin('goods as g','s.goods_id','=','g.id')
            ->select('s.num','s.surplus','s.level','s.is_special','g.name','g.image','g.price','g.is_book')->where('s.suit_id',$suit->id)->get()->each(function($item) use ($suit){
                $item->level_name = DB::table('level')->where('level',$item->level)->value('name');
                if($item->is_special == 0 && $item->surplus > 0){
                    $ratio = bcdiv($item->surplus,$suit->surplus,5);
                    $item->ratio = sprintf('%.2f',bcmul($ratio,100,3));
                }else{
                    $item->ratio = 0;
                }
            });
        $data['goods'] = $goods;
        return $this->ajax(1,'请求成功',$data);
    }

    //无限赏列表
    public function goodsList(Request $request){
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        $c_id = $request->input('c_id',0);
        $query = Box::query()->select('id','name','image','cover_image','price')->where(['type'=>3,'is_del'=>0,'state'=>1]);
        if($c_id){
            $query = $query->where('c_id',$c_id);
        }
        $query = $query->orderBy('sort','asc')->paginate($limit);
        return $this->ajax(1,'请求成功',[
            'count'=>$query->total(),
            'page'=>$page,
            'pages'=>$query->lastPage(),
            'data'=>$query->items(),
        ]);
    }

    //无限赏详情
    public function goodsDetail(Request $request){
        $id = $request->input('id');
        $token = $request->input('token');
        $uid = 0;
        if($token){
            $uid = User::query()->where('token',$token)->value('id');
        }
        $box = Box::query()->where('id',$id)->select('id','name','image','cover_image','price','type','hot')->first();
        $box->is_collection = 0;
        $collection = Collection::query()->where(['uid'=>$uid,'box_id'=>$id])->first();
        if($collection){
            $box->is_collection = 1;
        }
        $level = BoxLevel::query()->where('box_id',$id)->select('level','ratio')->get();
        $goods = Prize::query()->from('prize as p')->leftJoin('goods as g','p.goods_id','=','g.id')
            ->leftJoin('level as l','p.level','=','l.level')
            ->select('p.ratio','p.level','p.goods_id','g.name','g.image','g.price','g.is_book')->where('p.box_id',$id)
            ->orderBy('l.sort','asc')->orderBy('p.goods_id','asc')->get()->each(function ($item) use ($id){
                $item->ratio = BoxLevel::query()->where('box_id',$id)->where('level',$item->level)->value('ratio');
            });
        return $this->ajax(1,'请求成功',['box'=>$box,'level'=>$level,'goods'=>$goods]);
    }

    //无限赏-中奖记录
    public function awardList(Request $request){
        $box_id = $request->input('box_id');
        if(empty($box_id)) return $this->ajax(0,'参数错误');
        $lastLevel = Prize::query()->where('box_id',$box_id)->orderBy('id','desc')->value('level');
        $query = OrderGoods::query()->from('order_goods as o')->leftJoin('level as l','o.level','=','l.level')
            ->where('o.box_id',$box_id)->select('o.level','l.sort')->distinct('o.level')->orderBy('l.sort','asc')->get();
        foreach ($query as &$item){
            $item->is_end = 0;
            if($item->level == $lastLevel){
                $item->is_end = 1;
            }
            $item->goods = OrderGoods::query()->from('order_goods as o')->leftJoin('user as u','o.uid','=','u.id')
                ->select('u.nickname','u.avatar','o.name','o.level','o.level_name','o.number','o.create_time')
                ->where('o.box_id',$box_id)->where('o.level',$item->level)->orderBy('o.number','desc')->first();
            $item->state = 0;
        }
        return $this->ajax(1,'请求成功',$query);
    }

    //无限赏-中奖记录-展开
    public function awardListLog(Request $request){
        $box_id = $request->input('box_id');
        $level = $request->input('level');
        $goods = OrderGoods::query()->from('order_goods as o')->leftJoin('user as u','o.uid','=','u.id')
            ->select('u.nickname','u.avatar','o.name','o.level','o.level_name','o.number','o.create_time')
            ->where('o.box_id',$box_id)->where('o.level',$level)->orderBy('o.number','desc')->skip(1)->take(49)->get();
        return $this->ajax(1,'请求成功',$goods);
    }

    //无限赏-中奖记录-昵称搜索
    public function awardNickList(Request $request){
        $box_id = $request->input('box_id');
        $nickname = $request->input('nickname');
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        if(empty($box_id)) return $this->ajax(0,'参数错误');
        $query = OrderGoods::query()->from('order_goods as o')->leftJoin('user as u','o.uid','=','u.id')
                ->select('u.nickname','u.avatar','o.name','o.level','o.level_name','o.number','o.create_time')
                ->where('o.box_id',$box_id)->where('u.nickname','like','%'.$nickname.'%')->orderBy('o.number','desc')->paginate($limit);
        return $this->ajax(1,'请求成功',[
            'count'=>$query->total(),
            'page'=>$page,
            'pages'=>$query->lastPage(),
            'data'=>$query->items(),
        ]);
    }

    //无限赏-中奖记录-赏品搜索
    public function awardNameList(Request $request){
        $box_id = $request->input('box_id');
        $name = $request->input('name');
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        if(empty($box_id)) return $this->ajax(0,'参数错误');
        $query = OrderGoods::query()->from('order_goods as o')->leftJoin('user as u','o.uid','=','u.id')
            ->select('u.nickname','u.avatar','o.name','o.level','o.level_name','o.number','o.create_time')
            ->where('o.box_id',$box_id)->where('o.name','like','%'.$name.'%')->orderBy('o.number','desc')->paginate($limit);
        return $this->ajax(1,'请求成功',[
            'count'=>$query->total(),
            'page'=>$page,
            'pages'=>$query->lastPage(),
            'data'=>$query->items(),
        ]);
    }

    //扭蛋机列表
    public function eggList(Request $request){
        $limit = $request->input('limit',10);
        $page = $request->input('page',1);
        $query = DB::table('box')->select('id','name','image','cover_image','price')->where('type',4)->where('is_del',0)->where('state',1)
            ->orderBy('sort','asc')->paginate($limit);
        return $this->ajax(1,'请求成功',[
            'count'=>$query->total(),
            'page'=>$page,
            'pages'=>$query->lastPage(),
            'data'=>$query->items(),
        ]);
    }

    //扭蛋机详情
    public function eggDetail(Request $request){
        $id = $request->input('id');
        $box = DB::table('box')->where('id',$id)->select('id','name','image','cover_image','price')->first();
        $box->special = [];
        $special = OrderGoods::query()->from('order_goods as o')->leftJoin('user as u','o.uid','=','u.id')
            ->where('o.box_id',$id)->where('o.is_special',1)->select('u.id','u.nickname','u.avatar')->first();
        if($special){
            $box->special = $special;
        }
        $box->goods = Egg::query()->from('egg as e')->leftJoin('goods as g','e.goods_id','=','g.id')
            ->select('e.is_special','e.goods_id','g.name','g.image','g.price','g.is_book','g.content')->where('e.box_id',$id)
            ->orderBy('e.is_special','desc')->orderBy('e.goods_id','asc')->get();
        return $this->ajax(1,'请求成功',$box);
    }
}
