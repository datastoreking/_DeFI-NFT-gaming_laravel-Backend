<?php

namespace App\Http\Controllers\Admin;

use App\Models\Config;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function index(){
        $system_field = ['site_name','site_domain','site_logo','site_deliver','site_freight','site_free_num','site_notice','box_free','coupon_achieve',
            'site_group','site_share','is_coupon'];
        $data = [];
        foreach ($system_field as $value){
            $sys = Config::query()->where('name',$value)->first();
            $data[$value] = $sys['value'];
        }
        return view("admin.config.index",['data'=>$data]);
    }

    public function save(Request $request){
        $data = $request->post();
        try {
            foreach ($data as $key => $val) {
                Config::query()
                    ->where('name', $key)
                    ->update([
                        'value' => $val,
                    ]);
            }
            return $this->success("操作成功");
        } catch (\Exception $e) {
            return $this->success("保存失败");
        }
    }

}
