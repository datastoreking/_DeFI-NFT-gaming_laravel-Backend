<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoxAward extends Model
{
    protected $table = 'box_award';
    public $timestamps = false;
    protected $appends = [];

    public function getCreateTimeAttribute(){
        $value = $this->attributes['create_time'];
        return $value ? date('Y-m-d H:i:s',$value) : '';
    }
}
