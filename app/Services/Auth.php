<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;

/**
 * 权限验证服务
 * Class AuthService
 * @package app\common\services
 */
class Auth
{

    /**
     * 用户ID
     * @var null
     */
    protected $adminId = null;

    /**
     * 默认配置
     * @var array
     */
    protected $config = [
        'auth_on'          => true,              // 权限开关
        'admin'     => 'admin',    // 用户表
        'admin_auth'      => 'admin_auth',     // 权限表
        'admin_menu'      => 'admin_menu',     // 菜单节点表
        'admin_auth_node' => 'admin_auth_node',// 权限-节点表
    ];

    /***
     * 构造方法
     * AuthService constructor.
     * @param null $adminId
     */
    public function __construct($adminId = null)
    {
        $this->adminId = $adminId;
        return $this;
    }

    /**
     * 检测检测权限
     * @param null $node
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function checkNode($node = null)
    {
        // 判断是否为超级管理员
        if ($this->adminId == env('SUPER_ADMIN_ID')) {
            return true;
        }
        // 判断权限验证开关
        if ($this->config['auth_on'] == false) {
            return true;
        }
        // 判断是否需要获取当前节点
        if (empty($node)) {
            $node = $this->getCurrentNode();
        } else {
            $node = $this->parseNodeStr($node);
        }
        // 判断是否加入节点控制，优先获取缓存信息
        $nodeInfo = DB::table($this->config['admin_node'])
            ->where(['node' => $node])
            ->find();
        if (empty($nodeInfo)) {
            return false;
        }
        if ($nodeInfo['is_auth'] == 0) {
            return true;
        }
        // 用户验证，优先获取缓存信息
        $adminInfo = DB::table($this->config['admin'])
            ->where('id', $this->adminId)
            ->first();
        if (empty($adminInfo) || $adminInfo->status != 1 || empty($adminInfo->auth_ids)) {
            return false;
        }
        // 判断该节点是否允许访问
        $allNode = $this->getAdminNode();
        if (in_array($node, $allNode)) {
            return true;
        }
        return false;
    }

    /**
     * 获取当前节点
     * @return string
     */
    public function getCurrentNode()
    {
        $node = \Illuminate\Support\Facades\Request::path();
        return $node;
    }

    /**
     * 获取当前管理员所有节点
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAdminNode()
    {

        $nodeList = [];
        $adminInfo = DB::table($this->config['admin'])
            ->where([
                'id'     => $this->adminId,
                'status' => 1,
            ])->first();
        if (!empty($adminInfo)) {
            $buildAuthSql = DB::table($this->config['admin_auth'])
                ->distinct(true)
                ->where('id', $adminInfo->auth_ids)
                ->pluck('id');
            $buildAuthNodeSql = DB::table($this->config['admin_auth_node'])
                ->distinct(true)
                ->whereIn('auth_id' ,$buildAuthSql)
                ->pluck('menu_id');
            $nodeList = DB::table($this->config['admin_menu'])
                ->distinct(true)
                ->whereIn('id',$buildAuthNodeSql)
                ->pluck('href');
        }
        return $nodeList;
    }

    /**
     * 驼峰转下划线规则
     * @param string $node
     * @return string
     */
    public function parseNodeStr($node)
    {
        $array = explode('/', $node);
        foreach ($array as $key => $val) {
            if ($key == 0) {
                $val = explode('.', $val);
                foreach ($val as &$vo) {
                    $vo = Common::humpToLine(lcfirst($vo));
                }
                $val = implode('.', $val);
                $array[$key] = $val;
            }
        }
        $node = implode('/', $array);
        return $node;
    }

}
