<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderGoods;
use App\Models\OrderPay;
use App\Models\User;
use App\Models\UserDeal;
use Illuminate\Console\Command;

class PrizeReturn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prize_return';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '开奖失败返还';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('开奖失败返还开始');
        $orderList = Order::query()->where('is_prize',0)->where('pay_status',1)->where('type',1)->get();
        foreach ($orderList as $order){
            $orderGoods = OrderGoods::query()->where('oid',$order->id)->where('is_special',0)->select('name','image','level','level_name')->orderBy('number','desc')->limit(10)->get()->toArray();
            if(!$orderGoods){
                $user = User::query()->find($order->uid);
                $scorePay = OrderPay::query()->where('oid',$order->id)->where('pay_type','score')->first();
                if($scorePay){
                    $after_score = bcadd($user->score,$scorePay->pay_price,2);
                    UserDeal::insertDeal($user->id,1,$user->score,$scorePay->pay_price,$after_score,'抽赏失败返还'.intval($scorePay->pay_price).'潮玩币',2);
                    $user->increment('score',$scorePay->pay_price);
                }
                $balancePay = OrderPay::query()->where('oid',$order->id)->whereIn('pay_type',['balance','wechat'])->sum('pay_price');
                if($balancePay > 0){
                    $after_balance = bcadd($user->balance,$balancePay,2);
                    UserDeal::insertDeal($user->id,1,$user->balance,$balancePay,$after_balance,'抽赏失败余额返还',1);
                    $user->increment('balance',$balancePay);
                }
            }
            Order::query()->where('id',$order->id)->update(['is_prize'=>1]);
        }
        $this->info('开奖失败返还结束');
    }
}
