<?php

namespace App\Http\Middleware;

use Closure;

class ApiAuth
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
        if($request->header('authorization')==null){
            return response()->json(['code' => 9,'msg' => '请登录']);
        }
        if (auth()->guard('api')->guest()) {
            return response()->json(['code' => 9,'msg' => '请登录']);
        }
        return $next($request);
    }
}
