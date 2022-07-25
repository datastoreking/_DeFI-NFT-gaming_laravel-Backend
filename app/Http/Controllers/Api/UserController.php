<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;

use App\Models\Collection;
use App\Models\Config;
use App\Models\Coupon;
use App\Models\Deliver;
use App\Models\Goods;
use App\Models\Order;
use App\Models\OrderGoods;
use App\Models\Sale;
use App\Models\Suit;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserCoupon;
use App\Models\UserCouponLog;
use App\Models\UserDeal;
use App\Services\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //个人中心
    public function userInfo(){
        $id = auth()->guard('api')->id();
        $user = User::query()->where('id',$id)->select('id','nickname','avatar','mobile','balance','score')->first();
        $user->coupon = UserCoupon::query()->where('uid',$id)->where('state',0)->count();
        return $this->ajax(1,'请求成功',$user);
    }

    //修改头像
    public function updateAvatar(Request $request){
        $image = $request->input('image');
        $uid = auth()->guard('api')->id();
        if(empty($image)) return $this->ajax(0,'参数错误');

        $user = User::query()->find($uid);
        DB::beginTransaction();
        $user->avatar = $image;
        $res = $user->save();
        if($res){
            DB::commit();
            return $this->ajax(1,'修改成功');
        }else{
            DB::rollBack();
            return $this->ajax(0,'修改失败');
        }
    }

    //修改昵称
    public function updateNickname(Request $request){
        $nickname = $request->input('nickname');
        $uid = auth()->guard('api')->id();
        if(empty($nickname)) return $this->ajax(0,'昵称不能为空');

        $user = User::query()->find($uid);
        DB::beginTransaction();
        $user->nickname = $nickname;
        $res = $user->save();
        if($res){
            DB::commit();
            return $this->ajax(1,'修改成功');
        }else{
            DB::rollBack();
            return $this->ajax(0,'修改失败');
        }
    }

    //地址列表
    public function addressList(Request $request){
        $uid = auth()->guard('api')->id();
        $address = UserAddress::query()->where('uid',$uid)->select('id','username','mobile','province','city','area','address','is_default')->orderBy('is_default','desc')->get();
        return $this->ajax(1,'请求成功',$address);
    }

    //地址添加/修改
    public function addressHandle(Request $request){
        $uid = auth()->guard('api')->id();
        $id = $request->input('id');
        $username = $request->input('username');
        $mobile = $request->input('mobile');
        $province = $request->input('province');
        $city = $request->input('city');
        $area = $request->input('area');
        $address = $request->input('address');
        $is_default = $request->input('is_default',0);
        if(empty($username)) return $this->ajax(0,'收货人不能为空');
        if(empty($mobile)) return $this->ajax(0,'手机号不能为空');
        if(empty($province)) return $this->ajax(0,'省不能为空');
        if(empty($city)) return $this->ajax(0,'市不能为空');
        if(empty($area)) return $this->ajax(0,'区/县不能为空');
        if(empty($address)) return $this->ajax(0,'详细地址不能为空');
        $where['uid'] = $uid;
        $param['is_default'] = 0;
        if($is_default == 1){
            UserAddress::query()->where($where)->update($param);
        }
        DB::beginTransaction();
        if($id){
            $userAddress = UserAddress::query()->find($id);
        }else{
            $userAddress = new UserAddress();
            $userAddress->create_time = time();
        }
        $userAddress->uid = $uid;
        $userAddress->username = $username;
        $userAddress->mobile = $mobile;
        $userAddress->province = $province;
        $userAddress->city = $city;
        $userAddress->area = $area;
        $userAddress->address = $address;
        $userAddress->is_default = $is_default;
        if($userAddress->save()){
            DB::commit();
            return $this->ajax(1,'操作成功');
        }else{
            DB::rollBack();
            return $this->ajax(0,'操作失败');
        }
    }

    //地址删除
    public function addressDel(Request $request){
        $uid = auth()->guard('api')->id();
        $id = $request->input('id');
        if(empty($id)) return $this->ajax(0,'参数错误');

        DB::beginTransaction();
        $address = UserAddress::query()->where('id',$id)->where('uid',$uid)->first();
        $res = $address->delete();
        if($res){
            DB::commit();
            return $this->ajax(1,'删除成功');
        }else{
            DB::rollBack();
            return $this->ajax(0,'删除失败');
        }
    }

    //潮玩券-我的券
    public function couponList(){
        $uid = auth()->guard('api')->id();
        $list = UserCoupon::query()->where('uid',$uid)->where('state',0)->select('id','level','image','min_score','max_score','is_merge')->orderBy('is_merge','desc')->get()->each(function ($item){
            $item->state = 0;
        });
        return $this->ajax(1,'请求成功',$list);
    }

    //潮玩券-合并
    public function couponMerge(Request $request){
        $uid = auth()->guard('api')->id();
        $id = trim($request->input('id'),',');
        $id = explode(',',$id);
        if(count($id) < 2) return $this->ajax(0,'请选择两张或两张以上合并');

        $min_score = 0;
        $max_score = 0;
        $coupon = UserCoupon::query()->where('uid',$uid)->whereIn('id',$id)->get();
        foreach ($coupon as $item){
            if($item->is_merge == 1) return $this->ajax(0,'已合并券不可再次合并');
            $min_score += $item->min_score;
            $max_score += $item->max_score;
        }
        $res = Coupon::query()->first();
        $userCoupon = new UserCoupon();
        $userCoupon->uid = $uid;
        $userCoupon->cid = $res->id;
        $userCoupon->level = $res->level;
        $userCoupon->image = $res->image_merge;
        $userCoupon->min_score = $min_score;
        $userCoupon->max_score = $max_score;
        $userCoupon->state = 0;
        $userCoupon->create_time = time();
        $userCoupon->is_merge = 1;
        if($userCoupon->save()){
            UserCoupon::query()->where('uid',$uid)->whereIn('id',$id)->update(['state'=>2]);
            return $this->ajax(1,'合并成功');
        }else{
            return $this->ajax(0,'合并失败');
        }
    }

    //我的券-分享
    public function couponShare(Request $request){
        $id = $request->input('id');
        $uid = auth()->guard('api')->id();
        if(empty($id)) return $this->ajax(0,'参数错误');
        $coupon = UserCoupon::query()->where('id',$id)->where('uid',$uid)->first();
        if(!$coupon) return $this->ajax(0,'潮玩券不存在');
        if($coupon->state == 1) return $this->ajax(0,'已领取');

        $userCouponLog = UserCouponLog::query()->where('uid',$uid)->where('cid',$id)->first();
        if(!$userCouponLog){
            $userCouponLog = new UserCouponLog();
            $userCouponLog->cid = $id;
            $userCouponLog->uid = $uid;
            $userCouponLog->level = $coupon->level;
            $userCouponLog->image = $coupon->image;
            $userCouponLog->min_score = $coupon->min_score;
            $userCouponLog->max_score = $coupon->max_score;
            $userCouponLog->state = 0;
            $userCouponLog->create_time = time();
            $userCouponLog->save();
        }
        return $this->ajax(1,'分享成功');
    }

    //我的券-领取
    public function couponDraw(Request $request){
        $id = $request->input('id');
        $uid = auth()->guard('api')->id();
        if(empty($id)) return $this->ajax(0,'参数错误');
        $coupon = UserCoupon::query()->find($id);
        if($coupon->state == 1) return $this->ajax(0,'已领取');
        if($coupon->uid == $uid) return $this->ajax(0,'不可领取自己分享');
        if($coupon->state == 2) return $this->ajax(0,'已合并');
        $coupon->state = 1;

        $userCouponLog = new UserCouponLog();
        $userCouponLog->cid = $id;
        $userCouponLog->uid = $uid;
        $userCouponLog->level = $coupon->level;
        $userCouponLog->image = $coupon->image;
        $userCouponLog->min_score = $coupon->min_score;
        $userCouponLog->max_score = $coupon->max_score;
        $userCouponLog->is_merge = $coupon->is_merge;
        $userCouponLog->state = 0;
        $userCouponLog->create_time = time();

        $couponLog = new UserCouponLog();
        $couponLog->cid = $id;
        $couponLog->uid = $coupon->uid;
        $couponLog->level = $coupon->level;
        $couponLog->image = $coupon->image;
        $couponLog->min_score = $coupon->min_score;
        $couponLog->max_score = $coupon->max_score;
        $couponLog->is_merge = $coupon->is_merge;
        $couponLog->state = 0;
        $couponLog->create_time = time();
        if($coupon->save() && $userCouponLog->save() && $couponLog->save()){
            UserCouponLog::query()->where('cid',$id)->update(['state'=>1]);
            return $this->ajax(1,'领取成功');
        }else{
            return $this->ajax(0,'领取失败');
        }
    }

    //潮玩券-参与中
    public function couponJoin(){
        $uid = auth()->guard('api')->id();
        $list = UserCouponLog::query()->where('uid',$uid)->where('state',1)->select('id','level','min_score','max_score','is_merge')->orderBy('is_merge','desc')->get();
        return $this->ajax(1,'请求成功',$list);
    }

    //潮玩券-开券
    public function couponOpen(Request $request){
        $id = $request->input('id');
        $uid = auth()->guard('api')->id();
        if(empty($id)) return $this->ajax(0,'参数错误');
        $log = UserCouponLog::query()->where('id',$id)->where('uid',$uid)->first();
        if($log->state != 1) return $this->ajax(0,'不可开券');

        $log->state = 2;

        $score = rand($log->min_score,$log->max_score);
        $user = User::query()->find($uid);
        $after_score = bcadd($user->score,$score,2);
        $deal = UserDeal::insertDeal($uid,1,$user->score,$score,$after_score,'开券获得'.$score.'潮玩币',2);
        $user->increment('score',$score);
        if($log->save() && $deal && $user->save()){
            return $this->ajax(1,'开券成功',['score'=>$score]);
        }else{
            return $this->ajax(0,'开券失败');
        }
    }

    //潮玩币明细
    public function scoreList(Request $request){
        $uid = auth()->guard('api')->id();
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);

        $query = UserDeal::query()->where('uid',$uid)->where('way',2)
            ->select('info','create_time')->orderBy('id','desc')->paginate($limit);
        return $this->ajax(1,'请求成功',[
            'count'=>$query->total(),
            'page'=>$page,
            'pages'=>$query->lastPage(),
            'data'=>$query->items(),
        ]);
    }

    //我的账单
    public function dealList(Request $request){
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        $type = $request->input('type',0);
        $uid = auth()->guard('api')->id();
        $query = UserDeal::query()->where('uid',$uid)->where('way',1);
        if($type){
            $query = $query->where('type',$type);
        }
        $query = $query->select('amount','type','info','create_time')->orderBy('id','desc')->paginate($limit);
        return $this->ajax(1,'请求成功',[
            'count'=>$query->total(),
            'page'=>$page,
            'pages'=>$query->lastPage(),
            'data'=>$query->items(),
        ]);
    }

    //我的赏袋
    public function userBox(Request $request){
        $uid = auth()->guard('api')->id();
        $status = $request->input('status',1);
        $type = $request->input('type',0);
        $query = OrderGoods::query()->from('order_goods as o')
            ->leftJoin('goods as g','o.gid','=','g.id')
            ->where('o.uid',$uid)->where('o.status',$status);
        if($type){
            $type -= 1;
            $query = $query->where('g.is_book',$type);
        }
        $query = $query->select('o.gid')->distinct('o.gid')->get();
        foreach ($query as &$item){
            $item->state = 0;
            $orderGoods = OrderGoods::query()->where('uid',$uid)->where('status',$status)->where('gid',$item->gid)->first();
            $item->name = $orderGoods->name;
            $item->image = $orderGoods->image;
            $item->price = $orderGoods->price;
            $item->cost_price = $orderGoods->cost_price;
            $item->num = OrderGoods::query()->where('uid',$uid)->where('status',$status)->where('gid',$item->gid)->count();
        }
        return $this->ajax(1,'请求成功',$query);
    }

    //待操作赏袋
    public function waitUserBox(Request $request){
        $gid = trim($request->input('gid'),',');
        $uid = auth()->guard('api')->id();
        $status = $request->input('status',1);
        if(empty($gid)) return $this->ajax(0,'参数错误');
        $gid = explode(',',$gid);
        $data = [];
        foreach ($gid as $key=>$value){
            $orderGoods = OrderGoods::query()->where('uid',$uid)->where('gid',$value)->where('status',$status)->first();
            $data[$key]['gid'] = $value;
            $data[$key]['name'] = $orderGoods->name;
            $data[$key]['image'] = $orderGoods->image;
            $data[$key]['price'] = $orderGoods->price;
            $data[$key]['cost_price'] = $orderGoods->cost_price;
            $data[$key]['num'] = OrderGoods::query()->where('uid',$uid)->where('gid',$value)->where('status',$status)->count();
        }
        return $this->ajax(1,'请求成功',$data);
    }

    //赏袋发货
    public function userBoxDeliver(Request $request){
        $goodsData = json_decode($request->input('goodsData'),'true');
        $address_id = $request->input('address_id');
        $remark = $request->input('remark');
        $uid = auth()->guard('api')->id();
        if(empty($goodsData)) return $this->ajax(0,'参数错误');
        if(empty($address_id)) return $this->ajax(0,'请选择收货地址');
        $goodsData = Common::second_array_unique_bykey($goodsData,'gid');
        $user = User::query()->find($uid);
        $oid = '';
        $total_num = 0;
        foreach ($goodsData as $key=>$value){
            $total_num += $value['num'];
            $id = OrderGoods::query()->where('uid',$uid)->where('gid',$value['gid'])->where('status',1)->limit($value['num'])->pluck('id')->toArray();
            $oid .= implode(',',$id).',';
        }
        $freight = Config::getValue('site_freight');
        $free_freight = Config::getValue('site_free_num');
        $address = UserAddress::query()->find($address_id);
        $oid = trim($oid,',');
        if($total_num >= $free_freight){
            $orderGoods = OrderGoods::query()->whereIn('id',explode(',',$oid))->update(['status'=>3]);
            $deliver = new Deliver();
            $deliver->oid = $oid;
            $deliver->uid = $uid;
            $deliver->state = 1;
            $deliver->username = $address->username;
            $deliver->mobile = $address->mobile;
            $deliver->province = $address->province;
            $deliver->city = $address->city;
            $deliver->area = $address->area;
            $deliver->address = $address->address;
            $deliver->remark = $remark;
            $deliver->create_time = time();
            $deliver->data = json_encode($goodsData);
            if($orderGoods && $deliver->save()){
                return $this->ajax(1,'发货成功');
            }else{
                return $this->ajax(0,'发货失败');
            }
        }else{
            if($user->balance >= $freight){
                $after_balance = bcsub($user->balance,$freight,2);
                $deal = UserDeal::insertDeal($uid,2,$user->balance,$freight,$after_balance,'发货扣除余额',1);
                $user->decrement('balance',$freight);

                $orderGoods = OrderGoods::query()->whereIn('id',explode(',',$oid))->update(['status'=>3]);
                $deliver = new Deliver();
                $deliver->oid = $oid;
                $deliver->uid = $uid;
                $deliver->state = 1;
                $deliver->username = $address->username;
                $deliver->mobile = $address->mobile;
                $deliver->province = $address->province;
                $deliver->city = $address->city;
                $deliver->area = $address->area;
                $deliver->address = $address->address;
                $deliver->remark = $remark;
                $deliver->create_time = time();
                $deliver->data = json_encode($goodsData);
                if($deal && $user->save() && $orderGoods && $deliver->save()){
                    return $this->ajax(1,'发货成功');
                }else{
                    return $this->ajax(0,'发货失败');
                }
            }else{
                return $this->ajax(0,'余额不足');
            }
        }
    }

    //赏袋挂售
    public function userBoxSale(Request $request){
        $goodsData = json_decode($request->input('goodsData'),'true');
        $uid = auth()->guard('api')->id();
        if(empty($goodsData)) return $this->ajax(0,'参数错误');
        $goodsData = Common::second_array_unique_bykey($goodsData,'gid');
        $oid = '';
        $total_price = 0;
        foreach ($goodsData as $key=>$value){
            $num = OrderGoods::query()->where('uid',$uid)->where('gid',$value['gid'])->where('status',1)->count();
            $orderGoods = OrderGoods::query()->where('uid',$uid)->where('gid',$value['gid'])->where('status',1)->first();
            if($num < $value['num']) return $this->ajax(0,'赏品'.$orderGoods['name'].'数量不足');
            $id = OrderGoods::query()->where('uid',$uid)->where('gid',$value['gid'])->where('status',1)->limit($value['num'])->pluck('id')->toArray();
            $oid .= implode(',',$id).',';
            $total_price += bcmul($orderGoods['cost_price'],$value['num'],2);
        }
        DB::beginTransaction();
        $oid = trim($oid,',');
        $user = User::query()->find($uid);
        $after = bcadd($user->balance,$total_price,2);
        $deal = UserDeal::insertDeal($user->id,1,$user->balance,$total_price,$after,'挂售余额收入',1);
        $user->balance = $after;
        $res = OrderGoods::query()->whereIn('id',explode(',',$oid))->where('uid',$uid)->update(['status'=>5]);

        $sale = new Sale();
        $sale->oid = $oid;
        $sale->uid = $uid;
        $sale->data = json_encode($goodsData);
        $sale->total_price = $total_price;
        $sale->create_time = time();
        if($res && $deal && $user->save() && $sale->save()){
            DB::commit();
            return $this->ajax(1,'发货成功');
        }else{
            DB::rollBack();
            return $this->ajax(0,'发货失败');
        }
    }

    //添加到保险柜
    public function addSafe(Request $request){
        $goodsData = json_decode($request->input('goodsData'),'true');
        $uid = auth()->guard('api')->id();
        if(empty($goodsData)) return $this->ajax(0,'参数错误');
        $oid = '';
        foreach ($goodsData as $key=>$value){
            $id = OrderGoods::query()->where('uid',$uid)->where('gid',$value['gid'])->where('status',1)->limit($value['num'])->pluck('id')->toArray();
            $oid .= implode(',',$id).',';
        }
        $oid = trim($oid,',');
        $res = OrderGoods::query()->whereIn('id',explode(',',$oid))->where('uid',$uid)->update(['status'=>2]);
        if($res){
            return $this->ajax(1,'添加成功');
        }else{
            return $this->ajax(0,'添加失败');
        }
    }

    //移除保险柜
    public function removeSafe(Request $request){
        $goodsData = json_decode($request->input('goodsData'),'true');
        $uid = auth()->guard('api')->id();
        if(empty($goodsData)) return $this->ajax(0,'参数错误');
        $oid = '';
        foreach ($goodsData as $key=>$value){
            $id = OrderGoods::query()->where('uid',$uid)->where('gid',$value['gid'])->where('status',2)->limit($value['num'])->pluck('id')->toArray();
            $oid .= implode(',',$id).',';
        }
        $oid = trim($oid,',');
        $res = OrderGoods::query()->whereIn('id',explode(',',$oid))->where('uid',$uid)->update(['status'=>1]);
        if($res){
            return $this->ajax(1,'移除成功');
        }else{
            return $this->ajax(0,'移除失败');
        }
    }

    //发货记录
    public function deliverList(Request $request){
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        $uid = auth()->guard('api')->id();
        $state = $request->input('state',1);
        $query = Deliver::query()->where('uid',$uid)->where('state',$state)
            ->select('id','oid','create_time','username','mobile','province','city','area','address','express_name','express_number','data')->orderBy('id','desc')->paginate($limit);
        foreach ($query as &$item){
            $data = json_decode($item->data,true);
            foreach ($data as $key=>$value){
                $data[$key]['image'] = Goods::query()->where('id',$value['gid'])->value('image');
            }
            $item->data = $data;
        }
        return $this->ajax(1,'请求成功',[
            'count'=>$query->total(),
            'page'=>$page,
            'pages'=>$query->lastPage(),
            'data'=>$query->items(),
        ]);
    }

    //挂售记录
    public function saleList(Request $request){
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        $uid = auth()->guard('api')->id();
        $query = Sale::query()->where('uid',$uid)->select('create_time','data','total_price')->orderBy('id','desc')->paginate($limit);
        foreach ($query as &$item){
            $data = json_decode($item->data,true);
            foreach ($data as $k=>$v){
                $orderGoods = OrderGoods::query()->where('uid',$uid)->where('gid',$v['gid'])->first();
                $data[$k]['name'] = $orderGoods['name'];
                $data[$k]['image'] = $orderGoods['image'];
                $data[$k]['cost_price'] = $orderGoods['cost_price'];
            }
            $item->data = $data;
        }
        return $this->ajax(1,'请求成功',[
            'count'=>$query->total(),
            'page'=>$page,
            'pages'=>$query->lastPage(),
            'data'=>$query->items(),
        ]);
    }

    //收藏列表
    public function collectionList(Request $request){
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        $uid = auth()->guard('api')->id();
        $query = Collection::query()->from('collection as c')->leftJoin('box as b','c.box_id','=','b.id')
            ->select('c.box_id','c.type','b.name','b.image','b.cover_image','b.price','c.create_time')->where('c.uid',$uid)->orderBy('c.id','desc')->paginate($limit);
        foreach ($query as &$item){
            if($item->type == 1 || $item->type == 2){
                $item->total = Suit::query()->where('box_id',$item->box_id)->count();
                $item->surplus = Suit::query()->where('box_id',$item->box_id)->where('is_end',0)->count();
            }else{
                $item->total = 1;
                $item->surplus = 1;
            }
        }
        return $this->ajax(1,'请求成功',[
            'count'=>$query->total(),
            'page'=>$page,
            'pages'=>$query->lastPage(),
            'data'=>$query->items(),
        ]);
    }

    //收藏
    public function collection(Request $request){
        $uid = auth()->guard('api')->id();
        $box_id = $request->input('box_id');
        $type = $request->input('type');
        if(empty($box_id) || empty($type)) return $this->ajax(0,'参数错误');
        $res = Collection::query()->where('uid',$uid)->where('box_id',$box_id)->first();
        if($res) return $this->ajax(0,'已收藏');
        $collection = new Collection();
        $collection->uid = $uid;
        $collection->box_id = $box_id;
        $collection->type = $type;
        $collection->create_time = time();
        if($collection->save()){
            return $this->ajax(1,'收藏成功');
        }else{
            return $this->ajax(0,'收藏失败');
        }
    }

    //取消收藏
    public function collectionCancel(Request $request){
        $uid = auth()->guard('api')->id();
        $box_id = $request->input('box_id');
        if(empty($box_id)) return $this->ajax(0,'参数错误');
        $collection = Collection::query()->where('uid',$uid)->where('box_id',$box_id)->first();
        if(!$collection) return $this->ajax(0,'暂未收藏');
        $res = $collection->delete();
        if($res){
            return $this->ajax(1,'取消收藏成功');
        }else{
            return $this->ajax(0,'取消收藏失败');
        }
    }

    //退出登录
    public function logout(){
        $uid = auth()->guard('api')->id();
        $user = User::query()->find($uid);
        $user->token = '';
        if($user->save()){
            return $this->ajax(1,'退出成功');
        }else{
            return $this->ajax(0,'退出失败');
        }
    }

    //注销
    public function destroy(Request $request){
        $uid = auth()->guard('api')->id();

        $user = User::query()->find($uid);
        $user->is_del = 1;
        $user->token = '';
        if($user->save()){
            return $this->ajax(1,'注销成功');
        }else{
            return $this->ajax(0,'注销失败');
        }
    }
}
