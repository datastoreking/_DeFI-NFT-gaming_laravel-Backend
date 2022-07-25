<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param $msg
     * @return \Illuminate\Http\JsonResponse
     * @author xiaoyao
     * @date 2020/8/11 0011 下午 5:12`
     */
    public function success($msg){
        return response()->json(['type'=>'success','msg'=>$msg]);
    }

    /**
     * @param $msg
     * @return \Illuminate\Http\JsonResponse
     * @author xiaoyao
     * @date 2020/8/11 0011 下午 5:13
     */
    public function error($msg){
        return response()->json(['type'=>'error','msg'=>$msg]);
    }

    /**
     * @param $code
     * @param $msg
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     * @author xiaoyao
     * @date 2020/8/22 0022 下午 2:48
     */
    public function ajax($code,$msg,$data = array()){
        return response()->json(['code'=>$code,'msg'=>$msg,'data'=>$data]);
    }
}
