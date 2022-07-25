<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CountExport;
use App\Models\Deliver;
use App\Models\Express;
use App\Models\Goods;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderGoods;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function lists(Request $request){
        $limit = $request->input('limit',10);
        $order_no = $request->input('order_no');
        $nickname = $request->input('nickname');
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');

        $query = Order::query()->from('order as o')
            ->leftJoin('user as u','o.uid','=','u.id')
            ->select('o.*','u.nickname');
        if(!empty($order_no)){
            $query = $query->where('o.order_no',$order_no);
        }
        if(!empty($nickname)){
            $query = $query->where('u.nickname','like','%'.$nickname.'%');
        }
        if(!empty($start_time)){
            $query = $query->where('o.create_time','>',strtotime($start_time));
        }
        if(!empty($end_time)){
            $query = $query->where('o.create_time','<',strtotime($end_time." 23:59:59"));
        }
        $query = $query->where('o.pay_status',1)->orderBy('o.id','desc')->paginate($limit);
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    public function goods_lists(Request $request){
        $limit = $request->input('limit',10);
        $nickname = $request->input('nickname');
        $name = $request->input('name');
        $status = $request->input('status');
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');

        $query = OrderGoods::query()->from('order_goods as o')
            ->leftJoin('user as u','o.uid','=','u.id')
            ->select('o.*','u.nickname');
        if(!empty($nickname)){
            $query = $query->where('u.nickname','like','%'.$nickname.'%');
        }
        if(!empty($name)){
            $query = $query->where('o.name','like','%'.$name.'%');
        }
        if(!empty($status)){
            $query = $query->where('o.status',$status);
        }
        if(!empty($start_time)){
            $query = $query->where('o.create_time','>',strtotime($start_time));
        }
        if(!empty($end_time)){
            $query = $query->where('o.create_time','<',strtotime($end_time." 23:59:59"));
        }
        $query = $query->orderBy('o.id','desc')->paginate($limit);
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    public function deliver_lists(Request $request){
        $limit = $request->input('limit',10);
        $nickname = $request->input('nickname');
        $state = $request->input('state');
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');

        $query = Deliver::query()->from('deliver as d')
            ->leftJoin('user as u','d.uid','=','u.id')
            ->select('d.*','u.nickname');
        if(!empty($nickname)){
            $query = $query->where('u.nickname','like','%'.$nickname.'%');
        }
        if(!empty($state)){
            $query = $query->where('d.state',$state);
        }
        if(!empty($start_time)){
            $query = $query->where('d.create_time','>',strtotime($start_time));
        }
        if(!empty($end_time)){
            $query = $query->where('d.create_time','<',strtotime($end_time." 23:59:59"));
        }
        $query = $query->whereIn('d.state',[1,2])->orderBy('d.id','desc')->paginate($limit);
        foreach ($query as &$item){
            $item->address = $item->province.$item->city.$item->area.$item->address;
        }
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    public function deliver(Request $request){
        $id = $request->input('id');
        $deliver = Deliver::query()->find($id);
        $express = Express::query()->get();
        $goods = json_decode($deliver->data,true);
        foreach ($goods as $key=>$value){
            $res = Goods::query()->find($value['gid']);
            $goods[$key]['name'] = $res['name'];
        }
        return view('admin.order.deliver',['deliver'=>$deliver,'expressList'=>$express,'goodsList'=>$goods]);
    }

    public function postDeliver(Request $request){
        $id = $request->input('id');
        $express_id = $request->input('express_id');
        $express_number = $request->input('express_number');
        $deliver = Deliver::query()->find($id);
        if(empty($express_id)) return $this->error('请选择物流方式');
        if(empty($express_number)) return $this->error('物流单号不能为空');
        $express = Express::query()->find($express_id);
        $deliver->express_id = $express_id;
        $deliver->express_name = $express->name;
        $deliver->express_number = $express_number;
        if($deliver->state == 1){
            $deliver->state = 2;
        }

        if($deliver->save()){
            return $this->success('发货成功');
        }else{
            return $this->error('发货失败');
        }
    }
}
