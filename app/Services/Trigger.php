<?php
namespace App\Services;
use Illuminate\Support\Facades\Redis;

class Trigger
{

    /**
     * 更新菜单缓存
     * @param null $adminId
     * @return bool
     */
    public static function updateMenu($adminId = null)
    {
        if(empty($adminId)){
            Redis::set('initAdmin');
        }else{
            $redis = Redis::connection('initAdmin_' . $adminId);
            $redis->del();
        }
        return true;
    }

    /**
     * 更新节点缓存
     * @param null $adminId
     * @return bool
     */
    public static function updateNode($adminId = null)
    {
        if(empty($adminId)){
            Redis::set('authNode');
        }else{
            $redis = Redis::connection('allAuthNode_' . $adminId);
            $redis->del();
        }
        return true;
    }

    /**
     * 更新系统设置缓存
     * @return bool
     */
    public static function updateSysconfig($data)
    {
        Redis::connection('cache')->set('sysconfig',json_encode($data));
        return true;
    }

}
