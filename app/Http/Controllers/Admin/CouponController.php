<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coupon;
use App\Models\User;
use App\Models\UserCoupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function lists(Request $request){
        $limit = $request->input('limit',10);

        $query = Coupon::query();
        $query = $query->orderBy('id','asc')->paginate($limit);
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    public function add(Request $request){
        $id = $request->input('id');
        $coupon = Coupon::query()->find($id);
        return view('admin.coupon.add',['coupon'=>$coupon]);
    }

    public function postAdd(Request $request){
        $image = $request->input('image');
        $image_merge = $request->input('image_merge');
        $min_score = $request->input('min_score');
        $max_score = $request->input('max_score');
        $id = $request->input('id');
        $validator = Validator::make($request->input(),[
            'image'=>'required',
            'image_merge'=>'required',
            'min_score'=>'required',
            'max_score'=>'required',
            ],[
                'image.required'=>'请上传图片',
                'image_merge.required'=>'请上传合成图片',
                'min_score.required'=>'最小可得潮玩币不能为空',
                'max_score.required'=>'最大可得潮玩币不能为空',
        ]);
        if($validator->fails()){
            return $this->error($validator->errors()->first());
        }
        $coupon = Coupon::query()->find($id);
        $coupon->image = $image;
        $coupon->image_merge = $image_merge;
        $coupon->min_score = $min_score;
        $coupon->max_score = $max_score;
        $coupon->create_time = time();
        try{
            $coupon->save();
            return $this->success('操作成功');
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function give(){
        return view('admin.coupon.give');
    }

    public function postGive(Request $request){
        $uid = $request->input('uid');

        if(empty($uid)) return $this->error('请输入用户ID');

        $user = User::query()->find($uid);
        if(empty($user)) return $this->error('无此用户');

        $coupon = Coupon::query()->first();
        $userCoupon = new UserCoupon();
        $userCoupon->uid = $uid;
        $userCoupon->cid = $coupon->id;
        $userCoupon->level = $coupon->level;
        $userCoupon->image = $coupon->image;
        $userCoupon->min_score = $coupon->min_score;
        $userCoupon->max_score = $coupon->max_score;
        $userCoupon->state = 0;
        $userCoupon->create_time = time();
        $userCoupon->is_merge = 0;
        if($userCoupon->save()){
            return $this->success('赠送成功');
        }else{
            return $this->error('赠送失败');
        }
    }
}
