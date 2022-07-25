<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cash;
use App\Models\User;
use App\Models\UserDeal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CashController extends Controller
{
    public function lists(Request $request){
        $limit = $request->input('limit',10);
        $nickname = $request->input('nickname');
        $state = $request->input('state');
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');
        $query = Cash::query()->from('cash as c')
            ->leftJoin('user as u','c.uid','=','u.id')
            ->leftJoin('card as d','c.card_id','=','d.id')
            ->select('c.*','u.nickname','d.name','d.qrcode');
        if(!empty($nickname)){
            $query = $query->where('u.nickname','like','%'.$nickname.'%');
        }
        if(!empty($state)){
            $state -= 1;
            $query = $query->where('c.state',$state);
        }
        if(!empty($start_time)){
            $query = $query->where('c.create_time','>',strtotime($start_time));
        }
        if(!empty($end_time)){
            $query = $query->where('c.create_time','<',strtotime($end_time ."23:59:59"));
        }
        $query = $query->orderBy('c.id','desc')->paginate($limit);
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    public function pass(Request $request){
        $id = $request->input('id');
        $cash = Cash::query()->find($id);
        if($cash->state == 1) return $this->error('已审核通过');
        if($cash->state == 2) return $this->error('已审核拒绝');
        $admin = Session::get('admin');
        $cash->state = 1;
        $cash->admin_id = $admin['id'];
        $cash->update_time = time();
        if($cash->save()){
            return $this->success('审核成功');
        }else{
            return $this->error('审核失败');
        }
    }

    public function back(Request $request){
        $id = $request->input('id');
        $cash = Cash::query()->find($id);
        if($cash->state == 1) return $this->error('已审核通过');
        if($cash->state == 2) return $this->error('已审核拒绝');

        DB::beginTransaction();
        $admin = Session::get('admin');
        $cash->state = 2;
        $cash->admin_id = $admin['id'];
        $cash->update_time = time();

        $user = User::query()->find($cash->uid);

        $after_amount = bcadd($user->balance,$cash->amount,2);
        $deal = UserDeal::insertDeal($cash->uid,1,$user->balance,$cash->amount,$after_amount,'提现退回',1);

        $user->increment('balance',$cash->amount);
        if($cash->save() && $deal && $user->save()){
            DB::commit();
            return $this->success('审核成功');
        }else{
            DB::rollBack();
            return $this->error('审核失败');
        }
    }
}
