<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'config';
    public $timestamps = false;
    protected $appends = [];
    protected $hidden = [];

    public static function getValue($key){
        if(empty($key)) return false;
        $config = self::query()->where('name', $key)->first();
        if(empty($config)) return false;
        return $config->value;
    }

    public static function updateValueByKey($key,$value)
    {
        if (empty($key)) return false;
        $config = self::query()->where('name', $key)->first();
        if (empty($config)) {
            $config = new self();
        }
        $config->config_key = $key;
        $config->config_value = $value;
        $config->save();
        return true;
    }
}
