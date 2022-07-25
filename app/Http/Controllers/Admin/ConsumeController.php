<?php

namespace App\Http\Controllers\Admin;

use App\Models\Consume;
use App\Models\User;
use App\Services\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConsumeController extends Controller
{
    //消费日统计
    public function consume_day_lists(Request $request){
        $limit = $request->input('limit',10);
        $day = $request->input('day');
        if(empty($day)){
            $day = date('Y-m-d',time());
        }
        $time = explode('-',$day);
        $query = Consume::query()->from('consume as c')
            ->leftJoin('user as u','c.uid','=','u.id')
            ->where('c.year',$time[0])->where('c.month',$time[1])->where('c.day',$time[2])->where('c.type',1)
            ->select('c.*','u.avatar','u.nickname')
            ->orderBy('achieve','desc')->paginate($limit);
        $res = ($query->currentPage() - 1) * 10;
        foreach ($query as $key=>&$item){
            $item->rank = $res + $key + 1;
        }
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    //消费周统计
    public function consume_week_lists(Request $request){
        $limit = $request->input('limit',10);
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');

        if(empty($start_time) && empty($end_time)){
            $time = Common::getTimeStamp(2);
        }else{
            $time['start_time'] = strtotime($start_time);
            $time['end_time'] = strtotime($end_time." 23:59:59");
        }
        $query = Consume::query()->from('consume as c')->join('user as u','c.uid','=','u.id')
            ->whereBetween('c.create_time',[$time['start_time'],$time['end_time']])->where('c.type',1)->select('c.achieve','c.uid','u.avatar','u.nickname')
            ->selectRaw('SUM(dl_c.achieve) as num')->groupBy('c.uid')->orderBy('num','desc')->paginate($limit);
        $res = ($query->currentPage() - 1) * 10;
        foreach ($query as $key=>&$item){
            $item->rank = $res + $key + 1;
        }
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    //消费月统计
    public function consume_month_lists(Request $request){
        $limit = $request->input('limit',10);
        $month = $request->input('month');

        if(empty($month)){
            $time = Common::getTimeStamp(3);
        }else{
            $time['start_time'] = strtotime($month."-01");
            $day = date('t',strtotime($month."-01"));
            $time['end_time'] = strtotime($month.'-'.$day." 23:59:59");
        }
        $query = Consume::query()->from('consume as c')->join('user as u','c.uid','=','u.id')
            ->whereBetween('c.create_time',[$time['start_time'],$time['end_time']])->where('c.type',1)->select('c.achieve','c.uid','u.avatar','u.nickname')
            ->selectRaw('SUM(dl_c.achieve) as num')->groupBy('c.uid')->orderBy('num','desc')->paginate($limit);
        $res = ($query->currentPage() - 1) * 10;
        foreach ($query as $key=>&$item){
            $item->rank = $res + $key + 1;
        }
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    //打拳日统计
    public function times_day_lists(Request $request){
        $limit = $request->input('limit',10);
        $day = $request->input('day');
        if(empty($day)){
            $day = date('Y-m-d',time());
        }
        $time = explode('-',$day);
        $query = Consume::query()->from('consume as c')
            ->leftJoin('user as u','c.uid','=','u.id')
            ->where('c.year',$time[0])->where('c.month',$time[1])->where('c.day',$time[2])->where('c.type',3)
            ->select('c.*','u.avatar','u.nickname')
            ->orderBy('achieve','desc')->paginate($limit);
        $res = ($query->currentPage() - 1) * 10;
        foreach ($query as $key=>&$item){
            $item->rank = $res + $key + 1;
            $item->achieve = intval($item->achieve);
        }
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    //打拳周统计
    public function times_week_lists(Request $request){
        $limit = $request->input('limit',10);
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');

        if(empty($start_time) && empty($end_time)){
            $time = Common::getTimeStamp(2);
        }else{
            $time['start_time'] = strtotime($start_time);
            $time['end_time'] = strtotime($end_time." 23:59:59");
        }
        $query = Consume::query()->from('consume as c')->join('user as u','c.uid','=','u.id')
            ->whereBetween('c.create_time',[$time['start_time'],$time['end_time']])->where('c.type',3)->select('c.achieve','c.uid','u.avatar','u.nickname')
            ->selectRaw('SUM(dl_c.achieve) as num')->groupBy('c.uid')->orderBy('num','desc')->paginate($limit);
        $res = ($query->currentPage() - 1) * 10;
        foreach ($query as $key=>&$item){
            $item->rank = $res + $key + 1;
            $item->num = intval($item->num);
        }
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    //打拳月统计
    public function times_month_lists(Request $request){
        $limit = $request->input('limit',10);
        $month = $request->input('month');

        if(empty($month)){
            $time = Common::getTimeStamp(3);
        }else{
            $time['start_time'] = strtotime($month."-01");
            $day = date('t',strtotime($month."-01"));
            $time['end_time'] = strtotime($month.'-'.$day." 23:59:59");
        }
        $query = Consume::query()->from('consume as c')->join('user as u','c.uid','=','u.id')
            ->whereBetween('c.create_time',[$time['start_time'],$time['end_time']])->where('c.type',3)->select('c.achieve','c.uid','u.avatar','u.nickname')
            ->selectRaw('SUM(dl_c.achieve) as num')->groupBy('c.uid')->orderBy('num','desc')->paginate($limit);
        $res = ($query->currentPage() - 1) * 10;
        foreach ($query as $key=>&$item){
            $item->rank = $res + $key + 1;
            $item->num = intval($item->num);
        }
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    //无限日统计
    public function infinite_day_lists(Request $request){
        $limit = $request->input('limit',10);
        $day = $request->input('day');
        if(empty($day)){
            $day = date('Y-m-d',time());
        }
        $time = explode('-',$day);
        $query = Consume::query()->from('consume as c')
            ->leftJoin('user as u','c.uid','=','u.id')
            ->where('c.year',$time[0])->where('c.month',$time[1])->where('c.day',$time[2])->where('c.type',2)
            ->select('c.*','u.avatar','u.nickname')
            ->orderBy('achieve','desc')->paginate($limit);
        $res = ($query->currentPage() - 1) * 10;
        foreach ($query as $key=>&$item){
            $item->rank = $res + $key + 1;
        }
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    //无限周统计
    public function infinite_week_lists(Request $request){
        $limit = $request->input('limit',10);
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');

        if(empty($start_time) && empty($end_time)){
            $time = Common::getTimeStamp(2);
        }else{
            $time['start_time'] = strtotime($start_time);
            $time['end_time'] = strtotime($end_time." 23:59:59");
        }
        $query = Consume::query()->from('consume as c')->join('user as u','c.uid','=','u.id')
            ->whereBetween('c.create_time',[$time['start_time'],$time['end_time']])->where('c.type',2)->select('c.achieve','c.uid','u.avatar','u.nickname')
            ->selectRaw('SUM(dl_c.achieve) as num')->groupBy('c.uid')->orderBy('num','desc')->paginate($limit);
        $res = ($query->currentPage() - 1) * 10;
        foreach ($query as $key=>&$item){
            $item->rank = $res + $key + 1;
        }
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    //无限月统计
    public function infinite_month_lists(Request $request){
        $limit = $request->input('limit',10);
        $month = $request->input('month');

        if(empty($month)){
            $time = Common::getTimeStamp(3);
        }else{
            $time['start_time'] = strtotime($month."-01");
            $day = date('t',strtotime($month."-01"));
            $time['end_time'] = strtotime($month.'-'.$day." 23:59:59");
        }
        $query = Consume::query()->from('consume as c')->join('user as u','c.uid','=','u.id')
            ->whereBetween('c.create_time',[$time['start_time'],$time['end_time']])->where('c.type',2)->select('c.achieve','c.uid','u.avatar','u.nickname')
            ->selectRaw('SUM(dl_c.achieve) as num')->groupBy('c.uid')->orderBy('num','desc')->paginate($limit);
        $res = ($query->currentPage() - 1) * 10;
        foreach ($query as $key=>&$item){
            $item->rank = $res + $key + 1;
        }
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    //消费总流水日统计
    public function consume_total_day_lists(Request $request){
        $limit = $request->input('limit',10);
        $day = $request->input('day');
        if(empty($day)){
            $day = date('Y-m-d',time());
        }
        $time = explode('-',$day);
        $query = Consume::query()->from('consume as c')
            ->leftJoin('user as u','c.uid','=','u.id')
            ->where('c.year',$time[0])->where('c.month',$time[1])->where('c.day',$time[2])->whereIn('c.type',[1,2])
            ->select('c.achieve','c.uid','u.avatar','u.nickname')
            ->selectRaw('SUM(dl_c.achieve) as num')->groupBy('c.uid')->orderBy('num','desc')->paginate($limit);
        $res = ($query->currentPage() - 1) * 10;
        foreach ($query as $key=>&$item){
            $item->rank = $res + $key + 1;
        }
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    //消费总流水周统计
    public function consume_total_week_lists(Request $request){
        $limit = $request->input('limit',10);
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');

        if(empty($start_time) && empty($end_time)){
            $time = Common::getTimeStamp(2);
        }else{
            $time['start_time'] = strtotime($start_time);
            $time['end_time'] = strtotime($end_time." 23:59:59");
        }
        $query = Consume::query()->from('consume as c')->join('user as u','c.uid','=','u.id')
            ->whereBetween('c.create_time',[$time['start_time'],$time['end_time']])->whereIn('c.type',[1,2])
            ->select('c.achieve','c.uid','u.avatar','u.nickname')
            ->selectRaw('SUM(dl_c.achieve) as num')->groupBy('c.uid')->orderBy('num','desc')->paginate($limit);
        $res = ($query->currentPage() - 1) * 10;
        foreach ($query as $key=>&$item){
            $item->rank = $res + $key + 1;
        }
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    //消费月统计
    public function consume_total_month_lists(Request $request){
        $limit = $request->input('limit',10);
        $month = $request->input('month');

        if(empty($month)){
            $time = Common::getTimeStamp(3);
        }else{
            $time['start_time'] = strtotime($month."-01");
            $day = date('t',strtotime($month."-01"));
            $time['end_time'] = strtotime($month.'-'.$day." 23:59:59");
        }
        $query = Consume::query()->from('consume as c')->join('user as u','c.uid','=','u.id')
            ->whereBetween('c.create_time',[$time['start_time'],$time['end_time']])->whereIn('c.type',[1,2])
            ->select('c.achieve','c.uid','u.avatar','u.nickname')
            ->selectRaw('SUM(dl_c.achieve) as num')->groupBy('c.uid')->orderBy('num','desc')->paginate($limit);
        $res = ($query->currentPage() - 1) * 10;
        foreach ($query as $key=>&$item){
            $item->rank = $res + $key + 1;
        }
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }
}
