<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

/** Redis 操作集合 */
class RedisTool
{
    /** Redis 对象 */
    protected $redis;

    /**
     * 初始化链接Redis(自带laravel)
     *
     * @param string $name 链接数据库名称(不填默认defalult)
     */
    public function __construct()
    {
        $this->redis = new \Predis\Client();
    }

    /** --------------------------------- Redis 锁 ------------------------------------------------- */

    /**
     * 获取锁(悲观锁)
     * @param  String  $key    锁标识(要区分用户)
     * @param  Int     $expire 锁自动过期时间（防止死锁 & 减少无效数据堆积）
     * @param  Int     $num    重试次数
     * @return Boolean
     */
    public function block($key, $expire = 5, $intMaxTimes = 0)
    {
        //使用incr原子型操作加锁
        $intRet  = $this->redis->incr($key);

        // 这里不能设置全等于，类型可能有误
        if ($intRet == 1) {
            //设置过期时间，防止死任务的出现
            $this->redis->expire($key, $expire);
            return true;
        }
        if ($intMaxTimes > 0 && $intRet >= $intMaxTimes && $this->redis->ttl($key) === -1) {
            //当设置了最大加锁次数时，如果尝试加锁次数大于最大加锁次数并且无过期时间则强制解锁
            $this->redis->del($key);
        }
        return false;
    }

    /**
     * 释放锁
     * @param  String  $key 锁标识
     * @return Boolean
     */
    public  function unlock($key)
    {
        if ($this->redis->ttl($key)) {
            $this->redis->del($key);
        }
    }


    /**
     * 获取锁（乐观锁）
     * @param  String  $key    锁标识
     * @param  String  $num    销量
     * @return Boolean
     */
    public function llock($key)
    {
        $this->redis->watch($key);
        $sales = $this->get($key);
        // 判断Reids 库存是否有
        if (!$sales || ($sales <= 0)) {
            return false;
        }
        //开启事务
        $this->redis->multi();
        $this->incre($key, -1);         //将Redis 库存减一
        $res = $this->redis->exec();    //成功1 失败0
        return $res;
    }

    /** --------------------------------- String ------------------------------------------------- */

    /**
     * 增，设置值  构建一个字符串
     * @param string $key     KEY名称
     * @param string $value   设置值
     * @param int    $timeOut 时间 0表示无过期时间
     * @return true  总是返回true
     */
    public  function set($key, $value, $timeOut = 0)
    {
        $time = $this->redis->ttl($key);
        $setRes =  $this->redis->set($key, $value);
        if ($timeOut > 0) {
            $this->redis->expire($key, $timeOut);
        } else {
            if ($time > 0) {
                $this->redis->expire($key, $time);
            }
        }
        return $setRes;
    }

    /**
     * 查，获取 某键对应的值，不存在返回false
     * @param $key  键值
     * @return bool|string 查询成功返回信息，失败返回false
     */
    public  function get($key)
    {
        $setRes =  $this->redis->get($key); //不存在返回false
        if ($setRes === 'false') {
            return false;
        }
        return $setRes;
    }

    /** --------------------------------- lists类型 队列 ------------------------------------------------- */

    /**
     * 增，构建一个列表 先进后去，类似栈，将数据插入列表的头部 (建议使用)
     * @param string $key KEY名称
     * @param string $value 值
     * @param $timeOut |num  过期时间(整体)
     */
    public  function lpush($key, $value, $timeOut = 0)
    {
        $re = $this->redis->lpush($key, $value);
        if ($timeOut > 0) $this->redis->expire($key, $timeOut);
        return $re;
    }

    /**
     * 从尾部插入减少一个（建议使用）
     *
     * @param $key
     * @return string|null
     */

    public  function rpop($key)
    {
        return $this->redis->rPop($key);
    }

    /**
     * 增，构建一个列表(先进先去，类似队列，将数据插入列表的尾部)
     * @param string $key   KEY名称
     * @param string $value 值
     * @param $timeOut |num 过期时间（整体）
     */
    public  function rpush($key, $value, $timeOut = 0)
    {
        $re = $this->redis->rpush($key, $value);
        if ($timeOut > 0) $this->redis->expire($key, $timeOut);
        return $re;
    }

    /**
     * 从头部插入减少一个
     *
     * @param $key
     * @return string|null
     */
    public  function lpop($key)
    {
        return $this->redis->lpop($key);
    }

    /**
     * 查，获取所有列表数据（从头到尾取）默认全部
     * @param string $key    KEY名称
     * @param int    $start  开始
     * @param int    $end    结束
     */
    public  function lrange($key, $start = 0, $end = -1)
    {
        return $this->redis->lrange($key, $start, $end);
    }

    /**
     * 获取队列长度
     *
     * @param string $key
     * @return int
     */
    public  function llen($key)
    {
        return $this->redis->llen($key); // 获取队列长度
    }

    /**
     * 删除所有队列
     *
     * @param string $key
     * @return
     */
    public  function delAll($key)
    {
        $num = $this->redis->llen($key);
        for ($i = 0; $i < $num; $i++) {
            $this->redis->rpop($key);
        }
    }

    /** --------------------------------- 无序集合 ------------------------------------------------- */

    /**
     * 增，构建一个集合(无序集合) 和列表差不多，但列表值不唯一，集合唯一会覆盖
     * @param string       $key     集合Y名称
     * @param string|array $value   值
     * @param int          $timeOut 时间  0表示无过期时间
     * @return
     */
    public  function sadd($key, $value, $timeOut = 0)
    {
        $re = $this->redis->sadd($key, $value);
        if ($timeOut > 0) $this->redis->expire($key, $timeOut);
        return $re;
    }

    /**
     * 查，取集合对应元素
     * @param string $key 集合名字
     */
    public  function smembers($key)
    {
        $re = $this->redis->exists($key); //存在返回1，不存在返回0
        if (!$re) return false;
        return $this->redis->smembers($key);
    }

    /**
     * 判断元素是否存在该集合中
     * @param string $key 集合名字
     * @param string $value 元素名称
     */
    public  function sismember($key, $value)
    {
        $re = $this->redis->exists($key); //存在返回1，不存在返回0
        if (!$re) return false;
        return $this->redis->sismember($key, $value);
    }

    /** --------------------------------- 有序集合 ------------------------------------------------- */

    /**
     * 增，改，构建一个集合(有序集合),支持批量写入,更新
     * @param string $key           集合名称
     * @param array  $score_value   key为键, value为值
     * @return int   成功返回插入数量【更新操作返回0】
     */
    public  function zadd($key, $score_value, $timeOut = 0)
    {
        if (!is_array($score_value)) return false;
        $a = 0; //存放插入的数量
        foreach ($score_value as $score => $value) {
            $re =  $this->redis->zadd($key, $score, $value); //当修改时，可以修改，但不返回更新数量
            $re && $a += 1;
            if ($timeOut > 0) $this->redis->expire($key, $timeOut);
        }
        return $a;
    }

    /**
     * 查，有序集合查询，可升序降序,默认从第一条开始，查询指定区间元素
     * @param $key      查询的有序集合的键
     * @param $min      从第几个条开始（>=0,0从第一个）
     * @param $num      查询的个数(>=0,0为查询全部)
     * @param $order    asc表示升序排序，desc表示降序排序
     * @return array|bool   如果成功，返回查询信息，如果失败返回false
     */
    public  function zrange($key, $min = 0, $num = 0, $order = 'desc')
    {
        $re =  $this->redis->exists($key); //存在返回1，不存在返回0
        if (!$re) return false; //不存在键值
        if ('desc' == strtolower($order)) {
            $re = $this->redis->zrevrange($key, $min, $min + $num - 1);
        } else {
            $re = $this->redis->zrange($key, $min, $min + $num - 1);
        }
        if (!$re) return false; //查询的范围值为空
        return $re;
    }

    /**
     * 返回名称为key的有序集合中member（元素）的score（值）
     * @param $key
     * @param $member（元素）
     * @return string ,返回查询的member（元素）的score（值）
     */
    public  function zscore($key, $member)
    {
        return $this->redis->zscore($key, $member);
    }

    /**
     * 返回集合key中，元素位置（不是值/索引）
     * @param $key      有序集合键
     * @param $member   （元素）
     * @param $type     是顺序查找还是逆序
     * @return bool     键值不存在返回false，存在返回其排名下标
     */
    public  function zrank($key, $member, $type = 'desc')
    {
        $type = strtolower(trim($type));
        if ($type == 'desc') {
            $re = $this->redis->zrevrank($key, $member); //其中有序集成员按score值递减(从大到小)顺序排列，返回其排位
        } else {
            $re = $this->redis->zrank($key, $member); //其中有序集成员按score值递增(从小到大)顺序排列，返回其排位
        }
        if (!is_numeric($re)) return false; //不存在键值
        return $re;
    }

    /**
     * 返回名称为key有序集合中 score（值） >= star 且 值 <= end的所有元素（值区间）
     * @param $key      有序集合键
     * @param $member   （元素）
     * @param $star，
     * @param $end,
     * @return array
     */
    public  function zrangbyscore($key, $star, $end)
    {
        return $this->redis->zrangebyscore($key, $star, $end);
    }

    /** --------------------------------- 哈希 ------------------------------------------------- */

    /**
     * 增，以json格式插入数据到缓存,hash类型
     * @param $redis_key |array , $redis_key['key']数据库的表名称;$redis_key['field'],下标key
     * @param $token,该活动的token，用于区分标识
     * @param $id,该活动的ID，用于区分标识
     * @param $data|array ，要插入的数据,
     * @param $timeOut ，过期时间，默认为0
     * @return $number 插入成功返回1【,更新操作返回0】
     */
    public  function hset_json($redis_key, $token, $id, $data, $timeOut = 0)
    {
        $redis_table_name = $redis_key['key'] . ':' . $token;           //key的名称
        $redis_key_name = $redis_key['field'] . ':' . $id;              //field的名称，表示第几个活动
        $redis_info = json_encode($data);                           //field的数据value，以json的形式存储
        $re = $this->redis->hset($redis_table_name, $redis_key_name, $redis_info); //存入缓存
        if ($timeOut > 0) $this->redis->expire($redis_table_name, $timeOut); //设置过期时间
        return $re;
    }

    /**
     * 查，json形式存储的哈希缓存，有值则返回;无值则查询数据库并存入缓存
     * @param $redis,$redis['key'],$redis['field']分别是hash的表名称和键值
     * @param $token,$token为公众号
     * @param $token,$id为活动ID
     * @return bool|array, 成功返回要查询的信息，失败或不存在返回false
     */
    public  function hget_json($redis_key, $token, $id)
    {
        $re = $this->redis->hexists($redis_key['key'] . ':' . $token, $redis_key['field'] . ':' . $id); //返回缓存中该hash类型的field是否存在
        if ($re) {
            $info = $this->redis->hget($redis_key['key'] . ':' . $token, $redis_key['field'] . ':' . $id);
            $info = json_decode($info, true);
        } else {
            $info = false;
        }
        return $info;
    }

    /**
     * @param $redis_key
     * @param $name
     * @param $data
     * @param int $timeOut
     * @return \Predis\Client
     */
    public  function hset($redis_key, $name, $data, $timeOut = 0)
    {
        $re = $this->redis->hset($redis_key, $name, $data);
        if ($timeOut > 0) $this->redis->expire($redis_key, $timeOut);
        return $re;
    }

    /**
     * 增，普通逻辑的插入hash数据类型的值
     * @param $key ,键名
     * @param $data |array 一维数组，要存储的数据
     * @param $timeOut |num  过期时间
     * @return $number 返回OK【更新和插入操作都返回ok】
     */
    public  function hmset($key, $data, $timeOut = 0)
    {
        $re = $this->redis->hmset($key, $data);
        if ($timeOut > 0) $this->redis->expire($key, $timeOut);
        return $re;
    }

    /**
     * 查，普通的获取值
     * @param $key,表示该hash的下标值
     * @return array 。成功返回查询的数组信息，不存在信息返回false
     */
    public  function hval($key)
    {
        $re = $this->redis->exists($key); //存在返回1，不存在返回0
        if (!$re) return false;
        $vals = $this->redis->hvals($key);
        $keys = $this->redis->hkeys($key);
        $re = array_combine($keys, $vals);
        foreach ($re as $k => $v) {
            if (!is_null(json_decode($v))) {
                $re[$k] = json_decode($v, true); //true表示把json返回成数组
            }
        }
        return $re;
    }

    /**
     *获取单个
     * @param $key
     * @param $filed
     * @return bool|string
     */
    public  function hget($key, $filed = [])
    {
        if (empty($filed)) {
            $re = $this->redis->hgetAll($key);
        } elseif (is_string($filed)) {
            $re = $this->redis->hget($key, $filed);
        } elseif (is_array($filed)) {
            $re = $this->redis->hMget($key, $filed);
        }
        if (!$re) {
            return false;
        }
        return $re;
    }

    /**
     * 获取所有
     * @param $redis_key
     * @param $name
     * @param $data
     * @param int $timeOut
     * @return int
     */
    public  function hsetAll($redis_key, $name, $data, $timeOut = 0)
    {
        $re = $this->redis->hset($redis_key, $name, $data);
        if ($timeOut > 0) $this->redis->expire($redis_key, $timeOut);
        return $re;
    }

    /**
     * 删除指定的key
     * @param $redis_key
     * @param $name
     * @return int
     */
    public  function hdel($redis_key, $name)
    {
        $re = $this->redis->hdel($redis_key, $name);
        return $re;
    }

    /** --------------------------------- 公共方法 ------------------------------------------------- */

    /**
     * 删除缓存
     * @param string|array $key，键值
     * @param string       $type，类型，默认为常规，还有hash,zset
     * @param string       $field,hash=>表示$field值，set=>表示value,zset=>表示value值，list类型特殊暂时不加
     * @return int         返回删除的个数
     */
    public  function del($key, $type = "default", $field = '')
    {
        switch (strtolower(trim($type))) {
            // 哈希
            case 'hash':
                $re = $this->redis->hDel($key, $field); //返回删除个数
                break;
            // 集合
            case 'set':
                $re = $this->redis->sRem($key, $field); //返回删除个数
                break;
            // 有序集合
            case 'zset':
                $re = $this->redis->zDelete($key, $field); //返回删除个数
                break;
            // 字符串
            default:
                $re = $this->redis->del($key); //返回删除个数
                break;
        }
        return $re;
    }

    /**
     * 设置自增,自减功能
     * @param string    $key    要改变的键值
     * @param int       $num    改变的幅度，默认为1
     * @param string    $member 类型是zset或hash，需要在输入member或filed字段
     * @param string    $type   类型，default为普通增减,还有:zset,hash
     * @return bool|int 成功返回自增后的scroll整数，失败返回false
     */
    public  function incre($key, $num = 1, $member = '', $type = '')
    {
        $num = intval($num);
        switch (strtolower(trim($type))) {
            // 有序集合
            case "zset":
                $re = $this->redis->zincrby($key, $num, $member); //增长权值
                break;
            // 哈希
            case "hash":
                $re = $this->redis->hincrby($key, $member, $num); //增长hashmap里的值
                break;
            // string
            default:
                if ($num > 0) {
                    $re = $this->redis->incrby($key, $num); //默认增长
                } else {
                    $re = $this->redis->decrBy($key, -$num); //默认增长
                }
                break;
        }
        if ($re) return $re;
        return false;
    }

    /**
     * 检验某个键值是否存在
     * @param string $keys ，键值
     * @param string $type，类型，默认为常规
     * @param string $field。若为hash类型，输入$field
     * @return bool
     */
    public  function exists($keys, $type = '', $field = '')
    {
        switch (strtolower(trim($type))) {
            // 哈希
            case 'hash':
                $re = $this->redis->hexists($keys, $field); //有返回1，无返回0
                break;
            // 字符串
            default:
                $re = $this->redis->exists($keys);
                break;
        }
        return $re;
    }

    /**
     * 获取过期时间
     *
     * @param  string $key
     */
    public function ttl($key)
    {
        return $this->redis->ttl($key);
    }

    /**
     * 所有的key
     */
    public  function keyAll()
    {
        $re = $this->redis->keys('*');
        return $re;
    }

    /**
     * 清除缓存
     * @param int $type 默认为0，清除当前数据库；1表示清除所有缓存
     */
    public  function flush($type = 0)
    {
        if ($type) {
            $this->redis->flushAll(); //清除所有数据库
        } else {
            $this->redis->flushdb(); //清除当前数据库
        }
    }
}
