<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\AdminAuth;
use App\Http\Controllers\Controller;
use App\Services\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author xiaoyao
     * @date 2020/8/13 0013 下午 3:37
     */
    public function admin_lists(Request $request){
        $limit = $request->input('limit',10);
        $list = Admin::query()->leftJoin('admin_auth','admin_auth.id','admin.auth_ids');
        $list = $list->select('admin.*','admin_auth.title')->orderBy('sort','desc')->paginate($limit);
        return response()->json(['code' => 0, 'data' => $list->items(), 'count' => $list->total()]);
    }

    public function admin_add(Request $request){
        $id = $request->input('id',null);
        if(empty($id)){
            $admin = new Admin();
        }else{
            $admin = Admin::query()->find($id);
        }
        $role = AdminAuth::query()->get()->toArray();
        return view('admin.account.add',['admin'=>$admin,'roleList'=>$role]);
    }

    public function admin_postAdd(Request $request){
        $username = trim($request->input('username',null));
        $password = trim($request->input('password',null));
        $remark = trim($request->input('remark',null));
        $phone = trim($request->input('phone',null));
        $auth_ids = trim($request->input('auth_ids',null));

        $validator = Validator::make($request->input(),[
           'username'=>'required',
           'password'=>'required|min:6',
           'remark'=>'required',
           'phone'=>'required',
           'auth_ids'=>'required',
            ],[
            'username.required'=>'请输入账号',
            'password.required'=>'请输入密码',
            'password.min'=>'密码不少于6位',
            'remark.required'=>'请填写备注',
            'phone.required'=>'请输入手机号',
        ]);
        if($validator->fails()){
            return $this->error($validator->errors()->first());
        }
        if(empty($auth_ids)) return $this->error('请选择角色');
        if (!preg_match("/^1[34578]\d{9}$/", $phone)) {
            return $this->error('手机号,格式错误');
        }

        $result = Admin::query()->where('username',$username)->first();
        if(!empty($result)) return $this->error('账号已存在');

        $admin = new Admin();
        $admin->create_time = time();
        $password = Common::authcode($password);
        $admin->username = $username;
        $admin->password = $password;
        $admin->remark = $remark;
        $admin->phone = $phone;
        $admin->auth_ids = $auth_ids;

        if($admin->save()){
            return $this->success('操作成功');
        }else{
            return $this->error('操作失败');
        }
    }

    public function admin_edit(Request $request){
        $id = $request->input('id',null);
        if(empty($id)){
            $admin = new Admin();
        }else{
            $admin = Admin::query()->find($id);
        }
        $role = AdminAuth::query()->get()->toArray();
        return view('admin.account.edit',['admin'=>$admin,'roleList'=>$role]);
    }

    public function admin_postEdit(Request $request){
        $id = trim($request->input('id',null));
        $username = trim($request->input('username',null));
        $password = trim($request->input('password',null));
        $remark = trim($request->input('remark',null));
        $phone = trim($request->input('phone',null));
        $auth_ids = trim($request->input('auth_ids',null));

        $validator = Validator::make($request->input(),[
            'username'=>'required',
            'remark'=>'required',
            'phone'=>'required',
            'auth_ids'=>'required',
        ],[
            'username.required'=>'请输入账号',
            'remark.required'=>'请填写备注',
            'phone.required'=>'请输入手机号',
        ]);
        if($validator->fails()){
            return $this->error($validator->errors()->first());
        }
        if(empty($auth_ids)) return $this->error('请选择角色');
        if (!preg_match("/^1[34578]\d{9}$/", $phone)) {
            return $this->error('手机号,格式错误');
        }

        $admin = Admin::query()->find($id);
        if($password){
            $password = Common::authcode($password);
        }
        $admin->username = $username;
        $admin->password = $password;
        $admin->remark = $remark;
        $admin->phone = $phone;
        $admin->auth_ids = $auth_ids;

        if($admin->save()){
            return $this->success('操作成功');
        }else{
            return $this->error('操作失败');
        }
    }

    public function admin_status(Request $request)
    {
        $id   = trim($request->input('id'));
        $admin = Admin::query()->find($id);

        if ($admin->status == 1) {
            $admin->status = 2;
        } else {
            $admin->status = 1;
        }
        try {
            $bool  = $admin->save();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        if ($bool) {
            return $this->success('操作成功');
        } else {
            return $this->error('操作失败');
        }
    }
}
