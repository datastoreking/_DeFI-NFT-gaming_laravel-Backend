<?php
namespace App\Services;
use App\Models\Config;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class Common
{
    /**
     * @param $group
     * @param null $name
     * @return mixed
     * User: lyw@qq.com
     * Date: 2020/11/28 15:27
     * 获取系统配置信息
     */
    public static function sysconfig($group, $name = null)
    {
        $where = ['group' => $group];
        $value = empty($name) ? Redis::connection('cache')->get("config_{$group}") : Redis::connection('cache')->get("config_{$group}_{$name}");
        if (empty($value)) {
            if (!empty($name)) {
                $where['name'] = $name;
                $value = Config::query()->where($where)->value('value');
//                Redis::connection('cache')->set("config_{$group}_{$name}", $value, 3600);
            } else {
                $value = Config::query()->where($where)->pluck('value', 'name');
//                Redis::connection('cache')->set("config_{$group}", $value, 3600);
            }
        }
        return $value;
    }

    /**
     * 下划线转驼峰
     * @param $str
     * @return null|string|string[]
     */
    public static function lineToHump($str)
    {
        $str = preg_replace_callback('/([-_]+([a-z]{1}))/i', function ($matches) {
            return strtoupper($matches[2]);
        }, $str);
        return $str;
    }

    /**
     * 驼峰转下划线
     * @param $str
     * @return null|string|string[]
     */
    public static function humpToLine($str)
    {
        $str = preg_replace_callback('/([A-Z]{1})/', function ($matches) {
            return '_' . strtolower($matches[0]);
        }, $str);
        return $str;
    }

    /**
     * 密码加密算法
     * @param $value 需要加密的值
     * @param $type  加密类型，默认为md5 （md5, hash）
     * @return mixed
     */
    public static function password($value)
    {
        $value = sha1('blog_') . md5($value) . md5('_encrypt') . sha1($value);
        return sha1($value);
    }

    /**
     * @param string $string 要加密或解密的字符串
     * @param string $operation 加密 ENCODE 或者解密 DECODE
     * @param string $key 加解密秘钥
     * @param number $expiry 密码的位数
     * @return string
     */
    public static function authcode($string, $operation = 'ENCODE', $key = '', $expiry = 0)
    {
        $ckey_length = 4;
        $key = md5($key ? $key : 'default_key');
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = ($ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '');
        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);
        $string = ($operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string);
        $string_length = strlen($string);
        $result = '';
        $box = range(0, 255);
        $rndkey = array();
        for ($i = 0; $i <= 255; $i++) {
            $rndkey [$i] = ord($cryptkey [$i % $key_length]);
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box [$i] + $rndkey [$i]) % 256;
            $tmp = $box [$i];
            $box [$i] = $box [$j];
            $box [$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box [$a]) % 256;
            $tmp = $box [$a];
            $box [$a] = $box [$j];
            $box [$j] = $tmp;
            $result .= chr(ord($string [$i]) ^ $box [($box [$a] + $box [$j]) % 256]);
        }
        if ($operation == 'DECODE') {
            if (((substr($result, 0, 10) == 0) || (0 < (substr($result, 0, 10) - time()))) && (substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16))) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc . str_replace('=', '', base64_encode($result));
        }
    }

    public static function getTime(){
        $day = date('Y-m-d',time());
        $start = strtotime($day." 00:00:00");
        $end = strtotime($day." 23:59:59");
        $data['start'] = $start;
        $data['end'] = $end;
        return $data;
    }

    /*
     * 地图计算距离
     *  $lat1：起点纬度
     *  $lng1：起点经度
     *
     *  $lat2：终点纬度
     *  $lng2：终点经度
     * */
    public static function TX_Map_Api_distance($lat1, $lng1, $lat2, $lng2)
    {
        // 将角度转为狐度
        $radLat1 = deg2rad($lat1); // deg2rad()函数将角度转换为弧度
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137;
        return bcadd($s, 0, 2);
    }

    public static function queryLatLnt($address){
        $key = "23742b3c81476e0ff4cc7c7b00bdb7b4";
        $url = "https://restapi.amap.com/v3/geocode/geo?key=".$key."&address=".$address."&output=JSON";
        $data=file_get_contents($url);
        $result = json_decode($data,true);
        return $result;
    }

    public static function queryAddress($lng,$lat){
        $key = "23742b3c81476e0ff4cc7c7b00bdb7b4";
        $url = "https://restapi.amap.com/v3/geocode/regeo?output=JSON&location=".$lng.",".$lat."&key=".$key."&radius=1000&extensions=all";
        $data=file_get_contents($url);
        $result = json_decode($data,true);
        return $result;
    }


    /**
     * @param $type 1 微信app支付 2 微信小程序支付
     * @return array|string
     * User: lyw@qq.com
     * Date: 2021/3/23 15:49
     *
     */
    public static function getConfigPay($type){
        switch ($type){
            case 1:
                return [
                    'appid' => config('pay.wechat.appid'), // APP APPID
                    'mch_id' => config('pay.wechat.mch_id'),
                    'key' => config('pay.wechat.key'),
                    'notify_url' => config('pay.wechat.notify_url'),
                    'cert_client' => config('pay.wechat.cert_client'), // optional，退款等情况时用到
                    'cert_key' => config('pay.wechat.cert_key'),// optional，退款等情况时用到
                ];
                break;
            case 2:
                return [
                    'miniapp_id' => config('pay.wechat.miniapp_id'), // APP APPID
                    'appid' => config('pay.wechat.appid'), // APP APPID
                    'mch_id' => config('pay.wechat.mch_id'),
                    'key' => config('pay.wechat.key'),
                    'notify_url' => config('pay.wechat.notify_url'),
                    'cert_client' => config('pay.wechat.cert_client'), // optional，退款等情况时用到
                    'cert_key' => config('pay.wechat.cert_key'),// optional，退款等情况时用到
                ];
                break;
            default:
                return "未知状态";
                break;
        }
    }

    /**
     * @param $startdate
     * @param $enddate
     * @return array
     * User: lyw@qq.com
     * Date: 2021/3/27 17:01
     * 获取指定日期段内每一天的日期
     */
    public static function getDateFromRange($startdate, $enddate){
        $stimestamp = strtotime($startdate);
        $etimestamp = strtotime($enddate);
        // 计算日期段内有多少天
        $days = ($etimestamp-$stimestamp)/86400+1;
        // 保存每天日期
        $date = array();
        for($i=0; $i<$days; $i++){
            $date[] = date('Y-m-d', $stimestamp+(86400*$i));
        }
        return $date;
    }

    /**
     * @param $type 1 日 2 周 3 月 4 年 5 当月 6 昨日
     * @return array|string
     * User: lyw@qq.com
     * Date: 2021/3/27 14:58
     * 获取年月日起始时间戳
     */
    public static function getTimeStamp($type){
        $data=[];
        switch ($type){
            case 1:
                //php获取今日开始时间戳和结束时间戳
                $data['start_time']=mktime(0,0,0,date('m'),date('d'),date('Y'));
                $data['end_time']=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
                return $data;
                break;
            case 2:
                //php获取本周周起始时间戳和结束时间戳
                $data['start_time']=mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"));
                $data['end_time']=mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"));
                return $data;
                break;
            case 3:
                //php获取本月起始时间戳和结束时间戳
                $data['start_time']=mktime(0,0,0,date('m'),1,date('Y'));
                $data['end_time']=mktime(23,59,59,date('m'),date('t'),date('Y'));
                return $data;
                break;
            case 4:
                //php获取今年起始时间戳和结束时间戳
                $data['start_time']=mktime(0,0,0,1,1,date('Y',time()));
                $data['end_time']=mktime(23,59,59,12,31,date('Y',time()));
                return $data;
                break;
            case 5:
                //php获取今年起始时间戳和结束时间戳
                $data['start_time']=mktime(0,0,0,date('m'),1,date('Y'));
                $data['end_time']=mktime(23,59,59,date('m'),date('t'),date('Y'));
                return $data;
                break;
            case 6:
                //php获取今年起始时间戳和结束时间戳
                $data['start_time']=strtotime(date('Y-m-d', strtotime('-1 day')));//昨天开始时间戳
                $data['end_time']=strtotime(date('Y-m-d', strtotime('-1 day')). ' 23:59:59');//昨天结束时间戳
                return $data;
                break;
            default:
                return '未知状态';
                break;
        }

    }

    //抽奖算法
    public static function Prize($proArr){
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        // var_dump($proSum);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);  //返回随机整数

            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }

    //按照长度切割数组
    public static function arrSplit($data,$num){
        $arrRet = array();
        if( !isset( $data ) || empty( $data ) )
        {
            return $arrRet;
        }

        $iCount = count( $data )/$num;
        if( !is_int( $iCount ) )
        {
            $iCount = ceil( $iCount );
        }
        else
        {
            $iCount += 1;
        }
        for( $i=0; $i<$iCount;++$i )
        {
            $arrInfos = array_slice( $data, $i*$num, $num );
            if( empty( $arrInfos ) )
            {
                continue;
            }
            $arrRet[] = $arrInfos;
            unset( $arrInfos );
        }

        return $arrRet;
    }

    //二维数组去重
    public static function second_array_unique_bykey($arr, $key){
        $tmp_arr = array();
        foreach($arr as $k => $v)
        {
            if(in_array($v[$key], $tmp_arr))   //搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
            {
                unset($arr[$k]); //销毁一个变量  如果$tmp_arr中已存在相同的值就删除该值
            }
            else {
                $tmp_arr[$k] = $v[$key];  //将不同的值放在该数组中保存
            }
        }
        //ksort($arr); //ksort函数对数组进行排序(保留原键值key)  sort为不保留key值
        return $arr;

    }
}
