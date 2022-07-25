<?php

namespace App\Models;

use App\Services\Common;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';
    public $timestamps = false;


    public function setAdminAgency($uid,$data=[]){
        $admin = new self();
        $admin->username = $data['mobile'];
        $admin->phone = $data['mobile'];
        $admin->head_img = $data['avatar'];
        $admin->remark = $data['remark'];
        $admin->auth_ids = $data['auth_ids'];
        $admin->agency_id = isset($data['agency_id']) ? $data['agency_id'] : 0;
        $admin->ware_id = isset($data['ware_id']) ? $data['ware_id'] : 0;
        $admin->create_time = time();
        $admin->password = Common::authcode($data['mobile']);
        return $admin->save();
    }

}
