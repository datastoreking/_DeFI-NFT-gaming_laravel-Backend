<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    protected $table = 'order_address';
    public $timestamps = false;

    public function getCreateTimeAttribute(){
        return $this->attributes['create_time'] ?
            date('Y-m-d H:i:s',$this->attributes['create_time']) : '';
    }
}
