<?php

namespace App\Http\Controllers\Admin;

use App\Models\Active;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ActiveController extends Controller
{
    public function lists(Request $request){
        $limit = $request->input('limit',10);

        $query = Active::query();
        $query = $query->where('is_del',0)->orderBy('id','asc')->paginate($limit);
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    public function add(Request $request){
        $id = $request->input('id');
        if(empty($id)){
            $active = new Active();
        }else{
            $active = Active::query()->find($id);
        }
        return view('admin.active.add',['active'=>$active]);
    }

    public function postAdd(Request $request){
        $name = $request->input('name');
        $desc = $request->input('desc');
        $id = $request->input('id');
        $validator = Validator::make($request->input(),[
            'name'=>'required',
            'desc'=>'required',
            ],[
                'name.required'=>'活动名称不能为空',
                'desc.required'=>'活动描述不能为空',
        ]);
        if($validator->fails()){
            return $this->error($validator->errors()->first());
        }

        if(empty($id)){
            $active = new Active();
            $active->create_time = time();
        }else{
            $active = Active::query()->find($id);
        }
        $active->name = $name;
        $active->desc = $desc;
        try{
            $active->save();
            return $this->success('操作成功');
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function del(Request $request){
        $id = trim($request->input('id'));
        if(empty($id)) return $this->error('参数错误');
        $active = Active::query()->find($id);
        try{
            $active->is_del = 1;
            if($active->save()){
                return $this->success('删除成功');
            }else{
                return $this->error('删除失败');
            }
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }
}
