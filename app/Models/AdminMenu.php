<?php

namespace App\Models;

use App\Services\Tree;
use Illuminate\Database\Eloquent\Model;

class AdminMenu extends Model
{
    protected $table = 'admin_menu';
    public $timestamps = false;

    public static function getAuthNode($id)
    {
        $where['status'] = 1;
        $ruleList = self::query()->where($where)->select(['id','pid','title'])->get()->toArray();
        $mi = AdminAuthNode::query()->where(['auth_id'=>$id])->pluck('menu_id')->toArray();
        Tree::instance()->init($ruleList);
        return Tree::instance()->getTreeNode(0,$mi);
    }


}
