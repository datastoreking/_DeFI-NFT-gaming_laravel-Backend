<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AdController extends Controller
{
    public function lists(Request $request){
        $limit = $request->input('limit',10);

        $query = Ad::query();
        $query = $query->orderBy('id','asc')->paginate($limit);
        return response()->json(['code'=>0,'count'=>$query->total(),'data'=>$query->items()]);
    }

    public function add(Request $request){
        $id = $request->input('id');
        if(empty($id)){
            $ad = new Ad();
        }else{
            $ad = Ad::query()->find($id);
        }
        return view('admin.ad.add',['ad'=>$ad]);
    }

    public function postAdd(Request $request){
        $image = $request->input('image');
        $url = $request->input('url');
        $type = $request->input('type');
        $id = $request->input('id');
        $validator = Validator::make($request->input(),[
            'image'=>'required',
            'url'=>'required',
            'type'=>'required',
            ],[
                'image.required'=>'请上传图片',
                'url.required'=>'链接不能为空',
                'type.required'=>'请选择类型',
        ]);
        if($validator->fails()){
            return $this->error($validator->errors()->first());
        }

        if(empty($id)){
            $ad = new Ad();
            $ad->create_time = time();
        }else{
            $ad = Ad::query()->find($id);
        }
        $ad->image = $image;
        $ad->url = $url;
        $ad->type = $type;
        try{
            $ad->save();
            return $this->success('操作成功');
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function del(Request $request){
        $id = trim($request->input('id'));
        if(empty($id)) return $this->error('参数错误');
        $ad = Ad::query()->find($id);
        try{
            $res = $ad->delete();
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
