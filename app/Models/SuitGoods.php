<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuitGoods extends Model
{
    protected $table = 'suit_goods';
    public $timestamps = false;
    protected $appends = [];

    public function getCreateTimeAttribute(){
        $value = $this->attributes['create_time'];
        return $value ? date('Y-m-d H:i:s',$value) : '';
    }
}
