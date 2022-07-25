<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    protected $table = 'sms';
    public $timestamps = false;

    public static function createCode($length = 6){
        return str_pad(mt_rand(0,999999),$length,0,STR_PAD_BOTH);
    }

    public static function smsTemplate($event){
        switch ($event){
            case 'register':
                $template = 'SMS_201235248';
                break;
            case 'login':
                $template = 'SMS_201235250';
                break;
            case 'forget':
                $template = 'SMS_201235247';
                break;
            case 'modify':
                $template = 'SMS_201235247';
                break;
            case 'bind':
                $template = 'SMS_201235247';
                break;
            default:
                break;
        }
        return $template;
    }

    public static function verifySms($mobile,$event,$code){
        $map['mobile'] = $mobile;
        $map['event'] = $event;
        $map['code'] = $code;
        $sms = self::query()->where($map)->orderBy('id','desc')->first();
        $data['code'] = 1;
        if(empty($sms)){
            $data['code'] = 0;
            $data['msg'] = '验证码不正确';
        }
        if($sms->expire_time < time()){
            $data['code'] = 0;
            $data['msg'] = '验证码已过期';
        }
        return $data;
    }

    public static function tencentTemplate($event){
        switch ($event){
            case 'register':
                $template = '943631';
                break;
            case 'forget':
                $template = '943473';
                break;
            case 'modify':
                $template = '943475';
                break;
            default:
                break;
        }
        return $template;
    }
}
