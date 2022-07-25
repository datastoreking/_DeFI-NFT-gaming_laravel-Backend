<?php

namespace App\Http\Controllers\Admin;

use App\Models\Single;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SingleController extends Controller
{
    public function index(){
        return view('admin.single.index');
    }
    public function lists(Request $request){
        $limit = $request->input('limit',10);
        $query = Single::query()->orderBy('id','desc')->paginate($limit);
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    public function add(Request $request){
        $id = $request->input('id');
        $single = Single::query()->find($id);
        return view('admin.single.add',['single'=>$single]);
    }

    public function postAdd(Request $request){
        $id = $request->input('id');
        $title = trim($request->input('title',null));
        $content = $request->input('content',null);

        if(empty($title)) return $this->error('请输入标题');
        if(empty($content)) return $this->error('请输入内容');

        $single = Single::query()->find($id);
        $single->title = $title;
        $single->content = $content;

        if($single->save()){
            return $this->success('修改成功');
        }else{
            return $this->error('修改失败');
        }
    }
}
