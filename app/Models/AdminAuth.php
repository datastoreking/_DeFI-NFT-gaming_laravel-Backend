<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAuth extends Model
{
    protected $table = 'admin_auth';
    public $timestamps = false;

    public function getCreateTimeAttribute(){
        $value = $this->attributes['create_time'];
        return $value ? date('Y-m-d H:i:s',$value) : '';
    }

}
