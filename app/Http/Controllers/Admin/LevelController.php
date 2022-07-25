<?php

namespace App\Http\Controllers\Admin;

use App\Models\Level;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LevelController extends Controller
{
    public function lists(Request $request){
        $limit = $request->input('limit',10);

        $query = Level::query();
        $query = $query->orderBy('sort','asc')->paginate($limit);
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    public function add(Request $request){
        $id = $request->input('id');
        if(empty($id)){
            $result = new Level();
        }else{
            $result = Level::query()->find($id);
        }
        return view('admin.level.add',['result'=>$result]);
    }

    public function postAdd(Request $request){
        $name = $request->input('name');
        $sort = $request->input('sort');
        $level = $request->input('level');
        $id = $request->input('id');
        if(empty($name)) return $this->ajax(0,'请输入名称');
        if(empty($sort)) return $this->ajax(0,'请输入排序值');
        if(empty($level)) return $this->ajax(0,'请输入级别');

        if(empty($id)){
            if(Level::query()->where('level',$level)->exists()) return $this->error('赏品级别已存在');
            $result = new Level();
            $result->create_time = time();
        }else{
            $boxLevel = Level::query()->where('level',$level)->first();
            if($boxLevel && $boxLevel->id != $id) return $this->error('赏品级别已存在');
            $result = Level::query()->find($id);
        }
        $result->name = $name;
        $result->sort = $sort;
        $result->level = $level;
        try{
            $result->save();
            return $this->success('操作成功');
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function del(Request $request){
        $id = trim($request->input('id'));
        if(empty($id)) return $this->error('参数错误');
        $result = Level::query()->find($id);
        try{
            $res = $result->delete();
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
