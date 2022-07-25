<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDeal extends Model
{
    protected $table = 'user_deal';
    public $timestamps = false;

    public function getCreateTimeAttribute(){
        return $this->attributes['create_time'] ?
            date('Y-m-d H:i',$this->attributes['create_time']) : '';
    }

    public static function insertDeal($uid,$type,$before_amount,$amount,$after_amount,$info,$way){
        $deal = new self();
        $deal->uid = $uid;
        $deal->type = $type;
        $deal->before_amount = $before_amount;
        $deal->amount = $amount;
        $deal->after_amount = $after_amount;
        $deal->info = $info;
        $deal->way = $way;
        $deal->create_time = time();
        $res = $deal->save();
        return $res;
    }
}
