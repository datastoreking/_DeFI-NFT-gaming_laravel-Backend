<?php

namespace App\Models;

use App\Services\Common;
use App\Services\RedisTool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $table = 'order';
    public $timestamps = false;
    protected $appends = [];

    public function getCreateTimeAttribute(){
        return $this->attributes['create_time'] ? date('Y-m-d',$this->attributes['create_time']) : '';
    }
    public function getPayTimeAttribute(){
        return $this->attributes['pay_time'] ? date('Y-m-d H:i:s',$this->attributes['pay_time']) : '';
    }


    public static function orderNotify($order_no){
        //订单状态
        $order = self::query()->where('order_no',$order_no)->first();
        $order->pay_status = 1;
        $order->pay_time = time();
        $order->save();
        //支付金额扣除
        $user = User::query()->find($order->uid);
        $orderPay = OrderPay::query()->where('oid',$order->id)->get();
        foreach ($orderPay as $item){
            if($item->pay_type == 'balance'){
                $after_balance = bcsub($user->balance,$item->pay_price,2);
                UserDeal::insertDeal($user->id,2,$user->balance,$item->pay_price,$after_balance,'抽赏扣除余额',1);
                $user->decrement('balance',$item->pay_price);
            }else if($item->pay_type == 'score'){
                $after_score = bcsub($user->score,$item->pay_price,2);
                UserDeal::insertDeal($user->id,2,$user->score,$item->pay_price,$after_score,'抽赏抵扣'.intval($item->pay_price).'潮玩币',2);
                $user->decrement('score',$item->pay_price);
            }
        }
        $redisTools = new RedisTool();
        $times = Common::getTime();
        $today = date('Y-m-d',time());
        $today = explode('-',$today);
        if($order->type == 1) {
            $box = Box::query()->find($order->box_id);
            $field = ['id', 'ratio'];
            //普通赏
            if ($box->type == 1) {
                $suit = Suit::query()->find($order->suit_id);
                //数量不足终止回调
                if ($suit->surplus < $order->num) return false;
                $surplus = $suit->surplus - $order->num;
                if ($surplus == 0) {
                    $suit->is_end = 1;
                }
                $suit->surplus = $surplus;
                $suit->save();
                $data = [];
                $number = $redisTools->get('gamifly_suit_'.$order->suit_id);
                if(!$number){
                    $number = 1;
                }
                $next_number = $number + $order->num;
                $redisTools->set('gamifly_suit_'.$order->suit_id,$next_number);
                $goods = SuitGoods::query()->from('suit_goods as s')
                    ->leftJoin('goods as g','s.goods_id','=','g.id')
                    ->leftJoin('level as l','s.level','=','l.level')
                    ->select('s.*', 'g.name', 'g.image', 'g.price', 'g.cost_price', 'g.cost_price', 'l.name as level_name')
                    ->where('s.suit_id',$order->suit_id)->where('s.is_special',0)->where('s.surplus', '>', 0)->get()->toArray();
                //概率数组
                $ratio_arr = [];
                //商品数组
                $goods_arr = [];
                foreach ($goods as $key=>$value){
                    $value['ratio'] = $value['ratio'] * 1000;
                    $ratio_arr[$value['id']] = $value['ratio'];
                    $goods_arr[$value['id']] = $value;//概率数组
                }
                for ($i = 0; $i < $order->num; $i++) {
                    $rid = Common::Prize($ratio_arr); //根据概率获取奖项id
                    $goods_arr[$rid]['surplus'] -= 1;
                    $goods_arr[$rid]['sales'] += 1;
                    if($goods_arr[$rid]['surplus'] == 0){
                        unset($ratio_arr[$rid]);
                    }
                    $numbers = $number + $i;

                    $data[$i]['oid'] = $order->id;
                    $data[$i]['uid'] = $order->uid;
                    $data[$i]['gid'] = $goods_arr[$rid]['goods_id'];
                    $data[$i]['name'] = $goods_arr[$rid]['name'];
                    $data[$i]['image'] = $goods_arr[$rid]['image'];
                    $data[$i]['price'] = $goods_arr[$rid]['price'];
                    $data[$i]['cost_price'] = $goods_arr[$rid]['cost_price'];
                    $data[$i]['status'] = 1;
                    $data[$i]['box_id'] = $order->box_id;
                    $data[$i]['suit_id'] = $order->suit_id;
                    $data[$i]['level'] = $goods_arr[$rid]['level'];
                    $data[$i]['level_name'] = $goods_arr[$rid]['level_name'];
                    $data[$i]['is_special'] = 0;
                    $data[$i]['number'] = $numbers;
                    $data[$i]['create_time'] = time();
                }
                //更新剩余数量
                foreach ($goods_arr as $k=>$v){
                    SuitGoods::query()->where('id',$k)->update(['surplus'=>$v['surplus'],'sales'=>$v['sales']]);
                }
                DB::table('order_goods')->insert($data);
                $sales = OrderGoods::query()->where('suit_id', $order->suit_id)->count();
                //first赏
                $first = SuitGoods::query()->where('suit_id', $order->suit_id)->where('level', 'First')->where('surplus', '>', 0)->first();
                $half = $suit->num * 0.5;
                if ($first && $sales >= $half) {
                    $first->increment('sales', 1);
                    $first->decrement('surplus', 1);
                    $firstGoodsQuery = OrderGoods::query()->where('suit_id', $order->suit_id)->select('id', 'oid', 'uid')->limit($half)->get()->toArray();
                    shuffle($firstGoodsQuery);
                    $firstLog = array_shift($firstGoodsQuery);
                    $firstGoods = Goods::query()->find($first->goods_id);
                    $orderGoods = new OrderGoods();
                    $orderGoods->oid = $firstLog['oid'];
                    $orderGoods->uid = $firstLog['uid'];
                    $orderGoods->gid = $first->goods_id;
                    $orderGoods->name = $firstGoods->name;
                    $orderGoods->image = $firstGoods->image;
                    $orderGoods->price = $firstGoods->price;
                    $orderGoods->cost_price = $firstGoods->cost_price;
                    $orderGoods->status = 1;
                    $orderGoods->box_id = $order->box_id;
                    $orderGoods->suit_id = $order->suit_id;
                    $orderGoods->level = $first->level;
                    $orderGoods->level_name = Level::query()->where('level', $first->level)->value('name');
                    $orderGoods->is_special = 1;
                    $orderGoods->number = $suit->num + 1;
                    $orderGoods->create_time = time();
                    $orderGoods->save();
                }
                if ($surplus == 0) {
                    //是否有Last赏
                    $last = SuitGoods::query()->where('suit_id', $order->suit_id)->where('level', 'Last')->where('surplus', '>', 0)->first();
                    if ($last) {
                        $last->increment('sales', 1);
                        $last->decrement('surplus', 1);
                        $lastGoodsQuery = OrderGoods::query()->where('suit_id', $order->suit_id)->where('is_special', 0)->select('id', 'oid', 'uid')->orderBy('id','desc')->limit($half)->get()->toArray();
                        shuffle($lastGoodsQuery);
                        $lastLog = array_shift($lastGoodsQuery);
                        $lastGoods = Goods::query()->find($last->goods_id);
                        $orderGoods = new OrderGoods();
                        $orderGoods->oid = $lastLog['oid'];
                        $orderGoods->uid = $lastLog['uid'];
                        $orderGoods->gid = $last->goods_id;
                        $orderGoods->name = $lastGoods->name;
                        $orderGoods->image = $lastGoods->image;
                        $orderGoods->price = $lastGoods->price;
                        $orderGoods->cost_price = $lastGoods->cost_price;
                        $orderGoods->status = 1;
                        $orderGoods->box_id = $order->box_id;
                        $orderGoods->suit_id = $order->suit_id;
                        $orderGoods->level = $last->level;
                        $orderGoods->level_name = Level::query()->where('level', $last->level)->value('name');
                        $orderGoods->is_special = 1;
                        $orderGoods->number = $suit->num + 2;
                        $orderGoods->create_time = time();
                        $orderGoods->save();
                    }
                    //是否有全局赏
                    $w = SuitGoods::query()->where('suit_id', $order->suit_id)->where('level', 'W')->where('surplus', '>', 0)->first();
                    if ($w) {
                        $w->increment('sales', 1);
                        $w->decrement('surplus', 1);
                        $wGoodsQuery = OrderGoods::query()->where('suit_id', $order->suit_id)->select('id', 'oid', 'uid')->get()->toArray();
                        shuffle($wGoodsQuery);
                        $wLog = array_shift($wGoodsQuery);
                        $wGoods = Goods::query()->find($w->goods_id);
                        $orderGoods = new OrderGoods();
                        $orderGoods->oid = $wLog['oid'];
                        $orderGoods->uid = $wLog['uid'];
                        $orderGoods->gid = $w->goods_id;
                        $orderGoods->name = $wGoods->name;
                        $orderGoods->image = $wGoods->image;
                        $orderGoods->price = $wGoods->price;
                        $orderGoods->cost_price = $wGoods->cost_price;
                        $orderGoods->status = 1;
                        $orderGoods->box_id = $order->box_id;
                        $orderGoods->suit_id = $order->suit_id;
                        $orderGoods->level = $w->level;
                        $orderGoods->level_name = Level::query()->where('level', $w->level)->value('name');
                        $orderGoods->is_special = 1;
                        $orderGoods->number = $suit->num + 3;
                        $orderGoods->create_time = time();
                        $orderGoods->save();
                    }
                    $redisTools->del('gamifly_suit_'.$order->suit_id);
                }
            }
            //竞技赏
            if ($box->type == 2) {
                $consume_times = Consume::query()->where('uid', $order->uid)->where('type', 3)->whereBetween('create_time', [$times['start'], $times['end']])->first();
                $res = OrderGoods::query()->where('uid', $order->uid)->where('box_id', $order->box_id)->where('suit_id', $order->suit_id)->first();
                if (!$res) {
                    if ($consume_times) {
                        $consume_times->increment('achieve', 1);
                    } else {
                        $consume_times = new Consume();
                        $consume_times->uid = $order->uid;
                        $consume_times->year = $today[0];
                        $consume_times->month = $today[1];
                        $consume_times->day = $today[2];
                        $consume_times->achieve = 1;
                        $consume_times->type = 3;
                        $consume_times->create_time = time();
                        $consume_times->save();
                    }
                }

                $suit = Suit::query()->find($order->suit_id);
                $suitGoods = SuitGoods::query()->where('suit_id', $order->suit_id)->where('is_special', 0)->where('surplus', '>', 0)->first();
                //数量不足终止回调
                if ($suit->surplus < $order->num) return false;
                $surplus = $suit->surplus - $order->num;
                if ($surplus == 0) {
                    $suit->is_end = 1;
                }
                $suit->surplus = $surplus;
                $suit->save();
                $suitGoods->decrement('surplus',$order->num);
                $suitGoods->increment('sales',$order->num);
                $number_arr = $redisTools->get('gamifly_suit_'.$order->suit_id);
                if(!$number_arr){
                    $number_arr = [];
                    for ($j = 1; $j <= $suit->num; $j++) {
                        array_push($number_arr, $j);
                    }
                }else{
                    $number_arr = json_decode($number_arr,true);
                }
                $data = [];
                $new_arr = [];
                for($k = 0;$k<$order->num;$k++){
                    shuffle($number_arr);
                    $number = array_shift($number_arr);
                    array_push($new_arr,$number);
                }
                $redisTools->set('gamifly_suit_'.$order->suit_id,json_encode($number_arr));
                $goods = Goods::query()->find($suitGoods->goods_id);
                $level_name = Level::query()->where('level', $suitGoods->level)->value('name');
                for ($i = 0; $i < $order->num; $i++) {
                    $data[$i]['oid'] = $order->id;
                    $data[$i]['uid'] = $order->uid;
                    $data[$i]['gid'] = $suitGoods->goods_id;
                    $data[$i]['name'] = $goods->name;
                    $data[$i]['image'] = $goods->image;
                    $data[$i]['price'] = $goods->price;
                    $data[$i]['cost_price'] = $goods->cost_price;
                    $data[$i]['status'] = 1;
                    $data[$i]['box_id'] = $order->box_id;
                    $data[$i]['suit_id'] = $order->suit_id;
                    $data[$i]['level'] = $suitGoods->level;
                    $data[$i]['level_name'] = $level_name;
                    $data[$i]['is_special'] = 0;
                    $data[$i]['number'] = $new_arr[$i];
                    $data[$i]['create_time'] = time();
                }
                DB::table('order_goods')->insert($data);
                if ($surplus == 0) {
                    $specialSuitGoods = SuitGoods::query()->where('suit_id',$order->suit_id)->where( 'is_special',1)->where('surplus', '>', 0)->get();
                    foreach ($specialSuitGoods as $item) {
                        $suitGoods = SuitGoods::query()->find($item->id);
                        $suitGoods->increment('sales', $item->surplus);
                        $suitGoods->decrement('surplus', $item->surplus);
                        $goodsQuery = OrderGoods::query()->where('suit_id', $order->suit_id)->where('level', 'Challenge')->select('id', 'oid', 'uid')->get()->toArray();
                        for ($k=0;$k<$item->surplus;$k++){
                            shuffle($goodsQuery);
                            $challenge = array_shift($goodsQuery);
                            $warGoods = Goods::query()->find($item->goods_id);
                            $number = $suit->num + 1;
                            $last = OrderGoods::query()->where(['box_id' => $order->box_id, 'suit_id' => $order->suit_id, 'is_special' => 1])->orderBy('id', 'desc')->first();
                            if ($last) {
                                $number = $last->number + 1;
                            }
                            $orderGoods = new OrderGoods();
                            $orderGoods->oid = $challenge['oid'];
                            $orderGoods->uid = $challenge['uid'];
                            $orderGoods->gid = $item->goods_id;
                            $orderGoods->name = $warGoods->name;
                            $orderGoods->image = $warGoods->image;
                            $orderGoods->price = $warGoods->price;
                            $orderGoods->cost_price = $warGoods->cost_price;
                            $orderGoods->status = 1;
                            $orderGoods->box_id = $order->box_id;
                            $orderGoods->suit_id = $order->suit_id;
                            $orderGoods->level = $item->level;
                            $orderGoods->level_name = Level::query()->where('level', $item->level)->value('name');
                            $orderGoods->is_special = 1;
                            $orderGoods->number = $number;
                            $orderGoods->create_time = time();
                            $orderGoods->save();
                        }
                    }
                    $redisTools->del('gamifly_suit_'.$order->suit_id);
                }
            }
            //无限赏
            if ($box->type == 3) {
                $consume = Consume::query()->where('uid', $order->uid)->where('type', 2)->whereBetween('create_time', [$times['start'], $times['end']])->first();
                if ($consume) {
                    $consume->increment('achieve', $order->total_price);
                } else {
                    $consume = new Consume();
                    $consume->uid = $order->uid;
                    $consume->year = $today[0];
                    $consume->month = $today[1];
                    $consume->day = $today[2];
                    $consume->achieve = $order->total_price;
                    $consume->type = 2;
                    $consume->create_time = time();
                    $consume->save();
                }
                $boxAward = BoxAward::query()->where('box_id',$order->box_id)->where('is_award',0)->first();
                $award_number = 0;
                $is_box_award = 0;
                if($boxAward){
                    $award_number = $boxAward->number;
                }
                for ($i = 0; $i < $order->num; $i++) {
                    $number = 1;
                    $last = OrderGoods::query()->where(['box_id' => $order->box_id, 'suit_id' => $order->suit_id])->orderBy('id', 'desc')->first();
                    if ($last) {
                        $number = $last->number + 1;
                    }
                    if($number == $award_number){
                        $goods = Prize::query()->select('id', 'ratio')->where('box_id', $order->box_id)->where('level','SP')->get();
                        $is_box_award = 1;
                    }else{
                        $goods = Prize::query()->select('id', 'ratio')->where('box_id', $order->box_id)->get();
                    }

                    $arr = [];
                    foreach ($goods as $key => $value) {
                        $arr[$value->id] = $value->ratio * 10000;//概率数组
                    }
                    $rid = Common::Prize($arr); //根据概率获取奖项id
                    $prize = Prize::query()->find($rid);

                    $goods = Goods::query()->find($prize->goods_id);
                    $orderGoods = new OrderGoods();
                    $orderGoods->oid = $order->id;
                    $orderGoods->uid = $order->uid;
                    $orderGoods->gid = $prize->goods_id;
                    $orderGoods->name = $goods->name;
                    $orderGoods->image = $goods->image;
                    $orderGoods->price = $goods->price;
                    $orderGoods->cost_price = $goods->cost_price;
                    $orderGoods->status = 1;
                    $orderGoods->box_id = $order->box_id;
                    $orderGoods->suit_id = $order->suit_id;
                    $orderGoods->level = $prize->level;
                    $orderGoods->level_name = Level::query()->where('level', $prize->level)->value('name');
                    $orderGoods->is_special = 0;
                    $orderGoods->number = $number;
                    $orderGoods->create_time = time();
                    $orderGoods->save();
                    $box->increment('hot', 1);
                }
                if($is_box_award == 1){
                    $boxAward->is_award = 1;
                    $boxAward->save();
                }
            }
            //扭蛋机
            if ($box->type == 4) {
                for ($i = 0; $i < $order->num; $i++) {
                    $goods = Egg::query()->select('id', 'ratio')
                        ->where('box_id', $order->box_id)->where('is_special', 0)->where('surplus', '>', 0)->get();
                    $arr = [];
                    foreach ($goods as $key => $value) {
                        $arr[$value->id] = $value->ratio * 100;//概率数组
                    }
                    $rid = Common::Prize($arr); //根据概率获取奖项id
                    $egg = Egg::query()->find($rid);
                    $number = 1;
                    $last = OrderGoods::query()->where(['box_id' => $order->box_id, 'is_special' => 0])->orderBy('id', 'desc')->first();
                    if ($last) {
                        $number = $last->number + 1;
                    }
                    $goods = Goods::query()->find($egg->goods_id);
                    $orderGoods = new OrderGoods();
                    $orderGoods->oid = $order->id;
                    $orderGoods->uid = $order->uid;
                    $orderGoods->gid = $egg->goods_id;
                    $orderGoods->name = $goods->name;
                    $orderGoods->image = $goods->image;
                    $orderGoods->price = $goods->price;
                    $orderGoods->cost_price = $goods->cost_price;
                    $orderGoods->status = 1;
                    $orderGoods->box_id = $order->box_id;
                    $orderGoods->suit_id = $order->suit_id;
                    $orderGoods->is_special = 0;
                    $orderGoods->number = $number;
                    $orderGoods->create_time = time();
                    $orderGoods->save();

                    $egg->decrement('surplus', 1);
                    $egg->save();
                }
                $surplus = Egg::query()->select('id', 'ratio')
                    ->where('box_id', $order->box_id)->where('is_special', 0)->sum('surplus');
                if ($surplus == 0) {
                    $number = 1;
                    $last = OrderGoods::query()->where(['box_id' => $order->box_id, 'is_special' => 0])->orderBy('id', 'desc')->first();
                    if ($last) {
                        $number = $last->number + 1;
                    }
                    $goodsQuery = OrderGoods::query()->where('box_id', $order->box_id)->select('id', 'oid', 'uid')->get()->toArray();
                    shuffle($goodsQuery);
                    $query = array_shift($goodsQuery);
                    $egg = Egg::query()->where('box_id', $order->box_id)->where('is_special', 1)->first();
                    if ($egg) {
                        $goods = Goods::query()->find($egg->goods_id);
                        $orderGoods = new OrderGoods();
                        $orderGoods->oid = $query['oid'];
                        $orderGoods->uid = $query['uid'];
                        $orderGoods->gid = $egg->goods_id;
                        $orderGoods->name = $goods->name;
                        $orderGoods->image = $goods->image;
                        $orderGoods->price = $goods->price;
                        $orderGoods->cost_price = $goods->cost_price;
                        $orderGoods->status = 1;
                        $orderGoods->box_id = $order->box_id;
                        $orderGoods->suit_id = $order->suit_id;
                        $orderGoods->is_special = 1;
                        $orderGoods->number = $number;
                        $orderGoods->create_time = time();
                        $orderGoods->save();

                        $egg->decrement('surplus', 1);
                    }
                }
            }
            if($box->type == 1 || $box->type == 2 || $box->type == 4){
                //流水
                $consume = Consume::query()->where('uid', $order->uid)->where('type', 1)->whereBetween('create_time', [$times['start'], $times['end']])->first();
                if ($consume) {
                    $consume->increment('achieve', $order->total_price);
                } else {
                    $consume = new Consume();
                    $consume->uid = $order->uid;
                    $consume->year = $today[0];
                    $consume->month = $today[1];
                    $consume->day = $today[2];
                    $consume->achieve = $order->total_price;
                    $consume->type = 1;
                    $consume->create_time = time();
                    $consume->save();
                }
            }
            if(Config::getValue('is_coupon') == 1){
                $payLog = OrderPay::query()->where('oid', $order->id)->get();
                $total_pay = 0;
                foreach ($payLog as $log) {
                    if ($log->pay_type == 'balance' || $log->pay_type == 'wechat') {
                        $total_pay += $log->pay_price;
                    }
                }
                $user = User::query()->find($order->uid);
                $total_pay += $user->achieve;
                $times = floor($total_pay / Config::getValue('coupon_achieve'));
                for ($j = 0; $j < $times; $j++) {
                    $coupon = Coupon::query()->first();
                    $userCoupon = new UserCoupon();
                    $userCoupon->uid = $user->id;
                    $userCoupon->cid = $coupon->id;
                    $userCoupon->level = $coupon->level;
                    $userCoupon->image = $coupon->image;
                    $userCoupon->min_score = $coupon->min_score;
                    $userCoupon->max_score = $coupon->max_score;
                    $userCoupon->oid = $order->id;
                    $userCoupon->state = 0;
                    $userCoupon->create_time = time();
                    $userCoupon->is_merge = 0;
                    $userCoupon->save();
                }
                $user->achieve = $total_pay - $times * Config::getValue('coupon_achieve');
                $user->save();
            }
            if (in_array($box->type, [1, 2])) {
                $redis_key = 'gamifly_id_' . $order->suit_id;
                $redisTools->unlock($redis_key);
            }
        }
    }

    public static function getOrderSn()
    {
        $orderSn = strtoupper(dechex(date('m'))) . substr(time(), -5) .rand(1,10000) ;
        return $orderSn.date('His',time());
    }

    public static function getNonceStr($length = 7)
    {
        // 字符集，可任意添加你需要的字符
        $chars = '0123456789';
        return substr(str_shuffle($chars), 0, $length);
    }
}
