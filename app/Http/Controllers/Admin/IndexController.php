<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\AdminAuth;
use App\Models\AdminLogin;
use App\Models\AdminMenu;
use App\Models\Article;
use App\Models\Cash;
use App\Models\Goods;
use App\Models\Order;
use App\Models\Seller;
use App\Models\User;
use App\Models\UserAgency;
use App\Models\UserGroup;
use App\Models\UserWare;
use App\Services\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class IndexController extends Controller
{
    /**
     * 登录
     * account 用户名
     * password 密码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author xiaoyao
     * @date 2020/8/12 0012 上午 11:31
     */
    public function login(Request $request){
        $data = $request->only('account','password');
        $validator = Validator::make($data,[
            'account'=>'required',
            'password'=>'required',
            ],[
            'account.required' => '请输入账号',
            'password.required' => '请输入密码',
        ]);


        if($validator->fails()){
            return $this->error($validator->errors()->first());
        }
        $where['username'] = trim($data['account']);
        $admin = Admin::query()->where($where)->first();
        if (empty($admin)) {
            return $this->error('用户不存在');
        }

        if ($data['password'] != Common::authcode($admin->password,'DECODE')) {
            return $this->error('密码输入有误');
        }
        $admin_auth = AdminAuth::query()->where(['id'=>$admin->auth_ids])->first();
        if($admin->id !=1){
            if ($admin_auth->status == 1) {
                return $this->error('该角色已关闭');
            }
            if ($admin->status == 0) {
                return $this->error('账号已被禁用');
            }
        }

        $admin->login_num += 1;
        $admin->save();
        $admin = $admin->toArray();
        unset($admin['password']);
        $admin['expire_time'] = time() + 7200;
        Session::put('admin', $admin);
        return $this->success('登录成功');
    }

    /**
     * 首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author xiaoyao
     * @date 2020/8/12 0012 上午 11:32
     */
    public function index()
    {
        $where['status'] = 1;
        $where['pid'] = 0;
        $where['menu_status'] = 1;
        $admin = session('admin');
        $adminInfo = DB::table('admin')
            ->where([
                'id'     => $admin['id'],
                'status' => 1,
            ])->first();

        $buildAuthSql = DB::table('admin_auth')
            ->distinct(true)
            ->where('id', $adminInfo->auth_ids)
            ->pluck('id');

        $buildAuthNodeSql = DB::table('admin_auth_node')
            ->distinct(true)
            ->whereIn('auth_id' ,$buildAuthSql)
            ->pluck('menu_id');

        $list = AdminMenu::query()->where($where);
        if($admin['id'] != 1){
            $buildAuthNodeSql = json_encode($buildAuthNodeSql);
            $buildAuthNodeSql = json_decode($buildAuthNodeSql,true);
            $list->whereIn('id',$buildAuthNodeSql);
        }
        $list = $list->orderBy('sort','desc')->get();
        foreach ($list as $key => $item){
            $where['pid'] = $item->id;
            $menu_id = AdminMenu::query()->where(['pid'=>$item->id])->pluck('id');
            $menu = AdminMenu::query()->where($where);
            if($admin['id'] !=1){
                $arr = [];
                foreach ($menu_id as $value){
                    if(in_array($value,$buildAuthNodeSql)){
                        array_push($arr,$value);
                    }
                }
                $menu = $menu->whereIn('id',$arr);
            }
            $menu = $menu->orderBy('sort','desc')->get();
            $list[$key]['second'] = $menu;
        }
        return view('admin.index')->with("list",$list);
    }

    /**
     * 退出
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @author xiaoyao
     * @date 2020/8/12 0012 上午 11:32
     */
    public function logout()
    {
        Session::flush();
        return redirect('/admin/login')->withCookie('admin_id', '');
    }

    /**
     * 首页iframe数据
     * @return \Illuminate\Http\JsonResponse
     * @author xiaoyao
     * @date 2020/8/12 0012 上午 11:32
     */
    public function welcome(){

        //待提现金额
        $data['take_cash'] = Cash::query()->where(['state'=>0])->sum('amount');
        //昨天订单数
        $data['pre_day_order'] = Order::query()->whereBetween('create_time',[Common::getTimeStamp(6)['start_time'],Common::getTimeStamp(6)['end_time']])->where(['pay_status'=>1])->count();
        //昨日交易金额
        $data['pre_day_price'] = Order::query()->whereBetween('create_time',[Common::getTimeStamp(6)['start_time'],Common::getTimeStamp(6)['end_time']])->where(['pay_status'=>1])->sum('total_price');
        //订单金额 订单数趋势图
        $day_time2 = date('Y-m-d',time()).' 00:00:00';
        $qi_time2 = date('Y-m-d',(strtotime(date('Y-m-d',time())) - 30*3600*24)).' 00:00:00';
        $date_arr2 = Common::getDateFromRange($qi_time2,$day_time2);
        $order = [];
        foreach ($date_arr2 as $k=>$v){
            $start = strtotime($v.' 00:00:00');
            $end = strtotime($v.' 23:59:59');
            $order['date'][$k] = date('m-d',strtotime($v));
            //订单数
            $order['num'][$k] = Order::query()->where(['pay_status'=>1])->whereBetween('create_time',[$start,$end])->count();
            //订单金额
            $order['price'][$k] = Order::query()->where(['pay_status'=>1])->whereBetween('create_time',[$start,$end])->sum('total_price');
        }
        $data['order'] = json_encode($order);

        //今日新增会员
        $data['now_day'] = User::query()->whereBetween('create_time',[Common::getTimeStamp(1)['start_time'],Common::getTimeStamp(1)['end_time']])->count();
        //本月新增会员
        $data['month_day'] = User::query()->whereBetween('create_time',[Common::getTimeStamp(3)['start_time'],Common::getTimeStamp(3)['end_time']])->count();
        //用户新增趋势图
        $day_time = date('Y-m-d',time()).' 00:00:00';
        $qi_time = date('Y-m-d',(strtotime(date('Y-m-d',time())) - 15*3600*24)).' 00:00:00';
        $date_arr = Common::getDateFromRange($qi_time,$day_time);
        $add_user = [];
        foreach ($date_arr as $k=>$v){
            $start = strtotime($v.' 00:00:00');
            $end = strtotime($v.' 23:59:59');
            $add_user['date'][$k] = date('m-d',strtotime($v));
            //新增人数
            $add_user['num'][$k] = User::query()->whereBetween('create_time',[$start,$end])->count();
        }
        $data['add_user'] = json_encode($add_user);

        return $this->success($data);
    }
}
