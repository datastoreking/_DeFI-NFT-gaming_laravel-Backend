<?php

namespace App\Http\Middleware;
use App\Services\Auth;
use Closure;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $adminId = session('admin.id');
//        $expireTime = session('admin.expire_time');
        //获取访问路由
        $auth = new Auth($adminId);
        $node = $auth->getCurrentNode();
        $allnode = $auth->getAdminNode();
        $allnode = json_encode($allnode);
        $allnode_arr = json_decode($allnode,true);

        //设置
        if(!in_array($node,$allnode_arr) && $adminId !=1 && $node != 'admin/index' && $node != 'admin/logout' && $node != 'admin/welcome'){
            return $this->error('您没有相关权限执行该操作');
        }
        if(empty($adminId)){
            return redirect('/admin/login');
        }
        return $next($request);
    }

    /**
     * @param $msg
     * @return \Illuminate\Http\JsonResponse
     * @author xiaoyao
     * @date 2020/8/11 0011 下午 5:13
     */
    public function error($msg){
        return response()->json(['category'=>'error','msg'=>$msg]);
    }
}
