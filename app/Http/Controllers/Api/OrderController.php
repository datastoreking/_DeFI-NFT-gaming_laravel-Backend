<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Box;
use App\Models\Egg;
use App\Models\OrderPay;
use App\Models\Suit;
use App\Models\Config;
use App\Models\Order;
use App\Models\OrderGoods;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\UserDeal;
use App\Services\RedisTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yansongda\Pay\Pay;

class OrderController extends Controller
{
    //盲盒下单
    public function orderBox(Request $request){
        $uid = auth()->guard('api')->id();
        $box_id = $request->input('box_id');
        $type = $request->input('type');
        $suit_id = $request->input('suit_id',0);
        $num = $request->input('num',1);
        $is_score = $request->input('is_score',0);
        $is_balance = $request->input('is_balance',0);
        if(empty($box_id) || empty($type)) return $this->ajax(0,'参数错误');
        $box = Box::query()->find($box_id);
        if($type == 1 || $type == 2){
            $boxSuit = Suit::query()->find($suit_id);
            if($boxSuit->is_end == 1) return $this->ajax(0,'此箱已售罄');
            $saleNum = Order::query()->where(['box_id'=>$box_id,'suit_id'=>$suit_id,'pay_status'=>1,'uid'=>$uid])->sum('num');
            if($box->sale > 0 && $saleNum < $box->sale && $num > $box->sale) return $this->ajax(0,'不可全收');
            if($boxSuit->surplus < $num) return $this->ajax(0,'赏品不足');
        }else if($type == 4){
            $surplus = Egg::query()->where('box_id',$box_id)->where('is_special',0)->sum('surplus');
            if($surplus == 0) return $this->ajax(0,'已售罄');
            if($surplus < $num) return $this->ajax(0,'赏品不足');
        }
        $total_price = bcmul($box->price,$num,2);
        $real_price = $total_price;
        $user = User::query()->find($uid);
        $is_pay = 0;
        $score = 0;
        $balance = 0;
        if($is_score == 1){
            if($user->score > 0){
                if($user->score >= $real_price){
                    $score = $real_price;
                    $is_pay = 1;
                    $real_price = 0;
                }else{
                    $score = $user->score;
                    $real_price = bcsub($real_price,$user->score,2);
                }
            }
        }
        if($is_balance == 1 && $real_price > 0){
            if($user->balance >= $real_price){
                $balance = $real_price;
                $is_pay = 1;
            }
        }
        if($is_pay == 0) return $this->ajax(0,'账户余额不足');

        if(in_array($type,[1,2])){
            $redisTools = new RedisTool();
            $redis_key = 'gamifly_id_'.$suit_id;
            $lock = $redisTools->block($redis_key,5);
            if($lock){
                $order_no = Order::getOrderSn();
                $order = new Order();
                $order->uid = $uid;
                $order->order_no = $order_no;
                $order->type = 1;
                $order->num = $num;
                $order->total_price = $total_price;
                $order->create_time = time();
                $order->box_id = $box_id;
                $order->suit_id = $suit_id;
                if($order->save()){
                    if($score > 0){
                        $orderPay = new OrderPay();
                        $orderPay->oid = $order->id;
                        $orderPay->pay_price = $score;
                        $orderPay->pay_name = '潮玩币';
                        $orderPay->pay_type = 'score';
                        $orderPay->create_time = time();
                        $orderPay->save();
                    }
                    if($balance > 0){
                        $orderPay = new OrderPay();
                        $orderPay->oid = $order->id;
                        $orderPay->pay_price = $balance;
                        $orderPay->pay_name = '余额';
                        $orderPay->pay_type = 'balance';
                        $orderPay->create_time = time();
                        $orderPay->save();
                    }
                    Order::orderNotify($order_no);
                    return $this->ajax(1,'支付成功',['order_no'=>$order_no]);
                }else{
                    return $this->ajax(0,'支付失败');
                }
            }else{
                return $this->ajax(0,'他人正在购买');
            }
        }else{
            $order_no = Order::getOrderSn();
            $order = new Order();
            $order->uid = $uid;
            $order->order_no = $order_no;
            $order->type = 1;
            $order->num = $num;
            $order->total_price = $total_price;
            $order->create_time = time();
            $order->box_id = $box_id;
            $order->suit_id = $suit_id;
            if($order->save()){
                if($score > 0){
                    $orderPay = new OrderPay();
                    $orderPay->oid = $order->id;
                    $orderPay->pay_price = $score;
                    $orderPay->pay_name = '潮玩币';
                    $orderPay->pay_type = 'score';
                    $orderPay->create_time = time();
                    $orderPay->save();
                }
                if($balance > 0){
                    $orderPay = new OrderPay();
                    $orderPay->oid = $order->id;
                    $orderPay->pay_price = $balance;
                    $orderPay->pay_name = '余额';
                    $orderPay->pay_type = 'balance';
                    $orderPay->create_time = time();
                    $orderPay->save();
                }
                Order::orderNotify($order_no);
                return $this->ajax(1,'支付成功',['order_no'=>$order_no]);
            }else{
                return $this->ajax(0,'支付失败');
            }
        }
    }

    //开奖赏品查询
    public function drawPrize(Request $request){
        $uid = auth()->guard('api')->id();
        $order_no = $request->input('order_no');
        if(empty($order_no)) return $this->ajax(0,'参数错误');
        $order = Order::query()->where('uid',$uid)->where('order_no',$order_no)->first();
        if($order->pay_status == 1){
            $orderGoods = OrderGoods::query()->where('oid',$order->id)->where('is_special',0)->select('name','image','level','level_name')->orderBy('number','desc')->limit(10)->get()->toArray();
            if($orderGoods){
                return $this->ajax(1,'请求成功',$orderGoods);
            }else{
                $order->is_prize = 0;
                $order->save();
                return $this->ajax(0,'出赏结果异常，未出赏会返还余额');
            }

        }else{
            return $this->ajax(0,'暂未支付');
        }
    }

    //开奖优惠券查询
    public function drawCoupon(Request $request){
        $uid = auth()->guard('api')->id();
        $order_no = $request->input('order_no');
        if(empty($order_no)) return $this->ajax(0,'参数错误');

        $order = Order::query()->where('uid',$uid)->where('order_no',$order_no)->first();
        if($order->pay_status == 1){
            $coupon = UserCoupon::query()->where('oid',$order->id)->select('id','level','min_score','max_score','image')->get();
            return $this->ajax(1,'请求成功',$coupon);
        }else{
            return $this->ajax(0,'暂未支付');
        }
    }
}
