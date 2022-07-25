<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function lists(Request $request){
        $limit = $request->input('limit',10);

        $query = Category::query();
        $query = $query->paginate($limit);
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    public function add(Request $request){
        $id = $request->input('id');
        if(empty($id)){
            $result = new Category();
        }else{
            $result = Category::query()->find($id);
        }
        return view('admin.category.add',['result'=>$result]);
    }

    public function postAdd(Request $request){
        $name = $request->input('name');
        $type = $request->input('type');
        $id = $request->input('id');
        if(empty($name)) return $this->ajax(0,'请输入名称');
        if(empty($type)) return $this->ajax(0,'请选择类型');

        if(empty($id)){
            $result = new Category();
            $result->create_time = time();
        }else{
            $result = Category::query()->find($id);
        }
        $result->name = $name;
        $result->type = $type;
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
        $result = Category::query()->find($id);
        try{
            $result->is_del = 0;
            if($result->save()){
                return $this->success('删除成功');
            }else{
                return $this->error('删除失败');
            }
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }
}
