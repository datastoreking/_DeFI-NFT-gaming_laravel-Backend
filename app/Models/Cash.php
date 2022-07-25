<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    protected $table = 'cash';
    public $timestamps = false;
    public $appends = ['state_name'];

    public function getCreateTimeAttribute(){
        return $this->attributes['create_time'] ?
            date('Y-m-d H:i:s',$this->attributes['create_time']) : '';
    }

    public function getUpdateTimeAttribute(){
        return $this->attributes['update_time'] ?
            date('Y-m-d H:i:s',$this->attributes['update_time']) : '';
    }

    public function getStateNameAttribute(){
        switch ($this->attributes['state']){
            case 0:
                return "提现中";
                break;
            case 1:
                return "提现成功";
                break;
            case 2:
                return "提现失败";
                break;
            default:
                return "未知状态";
                break;
        }
    }
}
