<?php
namespace App\Http\Controllers\Admin;
use App\Models\User;
use App\Models\UserDeal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function lists(Request $request){
        $limit = trim($request->input('limit',10));
        $uid = trim($request->input('uid',null));
        $nickname = trim($request->input('nickname',null));
        $create_time = trim($request->input('create_time',null));
        $state = trim($request->input('state',null));
        $list = User::query();
        if(!empty($uid)){
            $list = $list->where('id',$uid);
        }
        if(!empty($nickname)){
            $list = $list->where('nickname','like','%'.$nickname.'%');
        }
        if(!empty($create_time)){
            $start = strtotime($create_time."00:00:00");
            $end = strtotime($create_time."23:59:59");
            $list = $list->whereBetween('create_time',[$start,$end]);
        }
        if(!empty($state)){
            $list = $list->where('state',$state);
        }
        $list = $list ->where('is_del',0) -> orderBy('id','desc')->paginate($limit);
        return response()->json(['code' => 0, 'data' => $list->items(), 'count' => $list->total()]);
    }

    public function state(Request $request){
        $id   = trim($request->get('id'));
        $user = User::query()->find($id);

        if ($user->state == 1) {
            $user->state = 2;
            $user->token = '';
        } else {
            $user->state = 1;
        }
        try {
            $bool  = $user->save();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        if ($bool) {
            return $this->success('操作成功');
        } else {
            return $this->error('操作失败');
        }
    }

    public function del(Request $request){
        $id = $request->input('id');
        $user = User::query()->find($id);
        if(empty($user)) return $this->error('参数错误');
        $user->is_del = 1;
        $user->token = '';
        if($user->save()){
            return $this->success('删除成功');
        }else{
            return $this->error('删除失败');
        }
    }

    public function conf(Request $request){
        $id = $request->input('id');
        $user = User::query()->find($id);
        return view('admin.user.conf',['user'=>$user]);
    }

    public function postConf(Request $request){
        $id = $request->input('id');
        $type = $request->input('type');//1余额 2潮玩币
        $value = $request->input('value',0);
        $way = $request->input('way');//1增加 2减少
        if($value <= 0) return $this->error('请输入要调整的数值');
        if(empty($type)) return $this->error('请选择调整类型');
        if(empty($way)) return $this->error('请选择调整方式');

        $user = User::query()->find($id);
        if($way == 2){
            if ($type == 1 && $user->balance < $value) return $this->error('可扣除的余额不足');
            if ($type == 2 && $user->score < $value) return $this->error('可扣除的潮玩币不足');
        };

        DB::beginTransaction();
        if($way == 1){
            if($type == 1){
                $before = $user->balance;
                $after = bcadd($before,$value,2);
            }else{
                $before = $user->score;
                $after = bcadd($before,$value,2);
            }
            $info = '管理员调整增加';
        }else{
            if($type == 1){
                $before = $user->balance;
                $after = bcsub($before,$value,2);
            }else{
                $before = $user->score;
                $after = bcsub($before,$value,2);
            }
            $info = '管理员调整扣除';
        }
        $deal = UserDeal::insertDeal($id,$way,$before,$value,$after,$info,$type);
        if($type == 1){
            $user->balance = $after;
        }else{
            $user->score = $after;
        }
        if($deal && $user->save()){
            DB::commit();
            return $this->success('操作成功');
        }else{
            DB::rollBack();
            return $this->error('操作失败');
        }
    }
}
