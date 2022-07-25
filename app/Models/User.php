<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Auth;

class User extends Auth
{
    protected $table = 'user';
    public $timestamps = false;
    protected $appends = [];
    protected $hidden = [];

    public function getCreateTimeAttribute(){
        $value = $this->attributes['create_time'];
        return $value ? date('Y-m-d',$value) : '';
    }

    public static function getUserByParam($where,$filed = '*'){
        $user = self::query()->where($where)->select($filed)->first();
        return $user;
    }

    public static function create(array $data){
        $user = new self();
        $user->nickname = $data['nickname'];
        $user->avatar = $data['avatar'];
        $user->balance = 0.00;
        $user->score = 0.00;
        $user->openid = $data['openid'];
        $user->create_time = time();
        $user->save();
        return $user;
    }

    public static function getToken(){
        $char = strtoupper(md5(uniqid(mt_rand(), true)));
        $token =  substr($char, 0, 8) . substr($char, 8, 4) . substr($char, 12, 4) . substr($char, 16, 4) . substr($char, 20, 12);
        return $token;
    }
}
