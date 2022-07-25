<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consume extends Model
{
    protected $table = 'consume';
    public $timestamps = false;
    protected $appends = [];

    public function getCreateTimeAttribute(){
        $value = $this->attributes['create_time'];
        return $value ? date('Y-m-d',$value) : '';
    }
}
