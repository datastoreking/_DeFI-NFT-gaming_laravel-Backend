<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    protected $table = 'box';
    public $timestamps = false;
    //protected $appends = ['category_name'];

    public function getCreateTimeAttribute(){
        $value = $this->attributes['create_time'];
        return $value ? date('Y-m-d',$value) : '';
    }
    public function getCategoryNameAttribute(){
        return $this->hasOne('App\Models\Category','id','c_id')->value('name');
    }
}
