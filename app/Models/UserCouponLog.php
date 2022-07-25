<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCouponLog extends Model
{
    protected $table = 'user_coupon_log';
    public $timestamps = false;

    public function getCreateTimeAttribute(){
        return $this->attributes['create_time'] ?
            date('Y-m-d H:i:s',$this->attributes['create_time']) : '';
    }
}
