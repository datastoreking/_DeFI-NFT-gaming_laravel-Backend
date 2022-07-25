<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deliver extends Model
{
    protected $table = 'deliver';
    public $timestamps = false;

    public function getCreateTimeAttribute(){
        return $this->attributes['create_time'] ?
            date('Y-m-d H:i:s',$this->attributes['create_time']) : '';
    }
}
