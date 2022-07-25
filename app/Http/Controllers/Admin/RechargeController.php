<?php

namespace App\Http\Controllers\Admin;

use App\Models\Recharge;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RechargeController extends Controller
{
    public function lists(Request $request){
        $limit = $request->input('limit',10);

        $query = Recharge::query();
        $query = $query->orderBy('id','asc')->paginate($limit);
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    public function add(Request $request){
        $id = $request->input('id');
        if(empty($id)){
            $recharge = new Recharge();
        }else{
            $recharge = Recharge::query()->find($id);
        }
        return view('admin.recharge.add',['recharge'=>$recharge]);
    }

    public function postAdd(Request $request){
        $amount = $request->input('amount');
        $id = $request->input('id');
        $validator = Validator::make($request->input(),[
            'amount'=>'required',
        ],[
            'amount.required'=>'金额不能位空',
        ]);
        if($validator->fails()){
            return $this->error($validator->errors()->first());
        }

        if(empty($id)){
            $rule = new Recharge();
            $rule->create_time = time();
        }else{
            $rule = Recharge::query()->find($id);
        }
        $rule->amount = $amount;
        try{
            $rule->save();
            return $this->success('操作成功');
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function del(Request $request){
        $id = trim($request->input('id'));
        if(empty($id)) return $this->error('参数错误');
        $recharge = Recharge::query()->find($id);
        try{
            $res = $recharge->delete();
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
