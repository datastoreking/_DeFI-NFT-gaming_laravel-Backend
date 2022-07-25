<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\AdminAuth;
use App\Models\AdminAuthNode;
use App\Models\AdminLogin;
use App\Http\Controllers\Controller;
use App\Models\AdminMenu;
use App\Services\Tree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author xiaoyao
     * @date 2020/8/13 0013 下午 3:37
     */
    public function auth_list(Request $request){
        $limit = $request->input('limit',10);
        $list = AdminAuth::query();
        $list = $list -> orderBy('sort','desc')->paginate($limit);
        return response()->json(['code' => 0, 'data' => $list->items(), 'count' => $list->total()]);
    }

    public function auth_add(Request $request){
        return view('admin.auth.add');
    }

    public function auth_postAdd(Request $request){
        $id = trim($request->input('id',null));
        $title = $request->input('title',null);
        $remark = $request->input('remark',null);
        $validator = Validator::make($request->input(),[
           'title'=>'required',
           'remark'=>'required',
            ],[
            'title.required'=>'请输入账号',
            'remark.required'=>'请输入密码',
        ]);
        if($validator->fails()){
            return $this->error($validator->errors()->first());
        }
        $auth = new AdminAuth();
        $auth->title = $title;
        $auth->remark = $remark;
        $auth->create_time = time();
        $auth->status = 2;

        if($auth->save()){
            return $this->success('操作成功');
        }else{
            return $this->error('操作失败');
        }
    }

    public function auth_edit(Request $request){
        $id = trim($request->input('id',null));
        $auth = AdminAuth::query()->find($id);
        return view('admin.auth.edit',['auth'=>$auth]);
    }

    public function auth_postEdit(Request $request){
        $id = trim($request->input('id',null));
        $title = $request->input('title',null);
        $remark = $request->input('remark',null);
        $auth = AdminAuth::query()->where('id',$id)->update(['title'=>$title,'remark'=>$remark,'update_time'=>time()]);
        if($auth){
            return $this->success('操作成功');
        }else{
            return $this->error('操作失败');
        }
    }


    public function auth_state(Request $request)
    {
        $id   = trim($request->input('id'));
        $auth = AdminAuth::query()->find($id);
        if ($auth->status == 1) {
            $auth->status = 2;
        } else {
            $auth->status = 1;
        }
        try {
            $bool  = $auth->save();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        if ($bool) {
            return $this->success('操作成功');
        } else {
            return $this->error('操作失败');
        }
    }

    public function auth_node(Request $request){
        $id = trim($request->input('id'));
        $auth = AdminAuth::query()->find($id);
        //使用菜单树
        $menuList = AdminMenu::getAuthNode($id);
        return view('admin.auth.node',['auth'=>$auth,'menuList'=>json_encode($menuList)]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * User: lyw@qq.com
     * Date: 2020/12/28 9:23
     * 权限节点
     */
    public function authPost(Request $request){
        $data = $request->all();
        AdminAuthNode::query()->where('auth_id',$data['id'])->delete();
        foreach($data as $key => $item){
            if($key != 'id'){
                $where['auth_id'] = $data['id'];
                $where['menu_id'] = $item;
                AdminAuthNode::query()->insert($where);
            }
        }
        return $this->success('操作成功');
    }
}
