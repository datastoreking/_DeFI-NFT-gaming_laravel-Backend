<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Single;
use App\Models\Sms;
use App\Models\User;
use App\Services\AliSms;
use App\Services\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommonController extends Controller
{

    /**
     * 发送验证码
     * @param $mobile 手机号
     * @param $event 事件
     * @author xiaoyao
     * @date 2021-03-10 14:56
     */
    public function sendSms(Request $request){
        $data = $request->input();
        $validator = Validator::make($data,[
            'mobile'=>'required',
            'event'=>'required',
        ],[
            'mobile.required'=>'手机号不能为空',
            'event.required'=>'发送事件不能为空'
        ]);
        if($validator->fails()){
            return $this->ajax(0,$validator->errors()->first());
        }
        if(!preg_match('/^1\d{10}$/',$data['mobile'])){
            return $this->ajax(0,'手机号格式错误');
        }

        $code = Sms::createCode();
        $user = User::getUserByParam(['mobile'=>$data['mobile'],'is_del'=>0]);
        if($data['event'] == 'register' && $user) return $this->ajax(0,'手机号已注册');
        if($data['event'] == 'forget' && !$user) return $this->ajax(0,'手机号未注册');
        $result = AliSms::sendSmsCode($data['mobile'],$code,Sms::smsTemplate($data['event']));
        if($result['Code'] == 'OK'){
            $sms = new Sms();
            $sms->mobile = $data['mobile'];
            $sms->code = $code;
            $sms->event = $data['event'];
            $sms->create_time = time();
            $sms->expire_time = strtotime("+10 minutes");
            $sms->save();
            return $this->ajax(1,'发送成功');
        }else{
            return $this->ajax(0,$result['SendStatusSet'][0]['Message']);
        }
    }

    /**
     * 上传图片
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author xiaoyao
     * @date 2021-03-10 17:22
     */
    public function upload(){
        if(!isset($_FILES['file'])) return $this->ajax(0,'请上传图片');
        if($_FILES['file']['error']) return $this->ajax(0,$_FILES['file']['error']);
        $ext = ['png','jpeg','jpg','gif'];
        if($_FILES['file']['size'] > 2097152) return $this->ajax(0,'文件最大为2M');
        $type = strtolower(substr($_FILES["file"]["name"],strrpos($_FILES["file"]["name"],'.')+1)); //得到文件类型，并且都转化成小写
        if(!in_array($type,$ext)) return $this->ajax(0,'图片类型错误');
        $name = time().rand(0,999999).".".$type;
        $dir = base_path()."/public/upload/images/".date("Ymd");
        if(!is_dir($dir)){
            mkdir($dir, 0777, true);
        }
        //防止文件名重复
        $filename = $dir."/".$name;
        //转码，把utf-8转成gb2312,返回转换后的字符串， 或者在失败时返回 FALSE。
        $filename =iconv("UTF-8","gb2312",$filename);
        //检查文件或目录是否存在
        if(file_exists($filename)) {
            return $this->ajax(0,"该文件已存在");
        }else{
            move_uploaded_file($_FILES["file"]["tmp_name"],$filename);
            $data['filename'] = "/upload/images/".date("Ymd")."/".$name;
            $data['filepath'] = URL("upload/images/".date("Ymd")."/".$name);
            return $this->ajax(1,'上传成功',$data);
        }
    }

    /**
     * 地址获取经纬度
     * @param address 地址
     * @author xiaoyao
     * @date 2021-03-11 15:40
     */
    public function queryLngLat(Request $request){
        $address = $request->input('address');
        if(empty($address)) return $this->ajax(0,'地址不能为空');
        $res = Common::queryLatLnt($address);
        if($res['status'] == 1 && $res['infocode'] == 10000){
            $geocodes = explode(',',$res['geocodes'][0]['location']);
            return $this->ajax(1,'请求成功',['lng'=>$geocodes[0],'lat'=>$geocodes[1]]);
        }else{
            return $this->ajax(0,$res['info']);
        }
    }

    /**
     * 经纬度查询地址
     * @param lng 经度
     * @param lat 纬度
     * @author xiaoyao
     * @date 2021-03-11 15:44
     */
    public function queryAddress(Request $request){
        $lng = $request->input('lng');
        $lat = $request->input('lat');
        if(empty($lng) || empty($lat)) return $this->ajax(0,'经纬度不能为空');

        $res = Common::queryAddress($lng,$lat);
        if($res['status'] == 1 && $res['infocode'] == 10000){
            $data['province'] = $res['regeocode']['addressComponent']['province'];
            $data['city'] = $res['regeocode']['addressComponent']['city'];
            $data['area'] = $res['regeocode']['addressComponent']['district'];
            $data['number'] = $res['regeocode']['addressComponent']['streetNumber']['number'];
            $data['street'] = $res['regeocode']['addressComponent']['streetNumber']['street'];
            $data['name'] = $res['regeocode']['addressComponent']['building']['name'];
            return $this->ajax(1,'请求成功',$data);
        }else{
            return $this->ajax(0,$res['info']);
        }
    }

    /**
     * @param id 单页ID
     * @author xiaoyao
     * @date 2021-03-12 15:30
     */
    public function single(Request $request){
        $id = $request->input('id');
        if(empty($id)) return $this->ajax(0,'参数错误');
        $single = Single::query()->find($id);
        return $this->ajax(1,'请求成功',$single);
    }

    //下载链接
    public function downloadUrl(Request $request){
        $type = $request->input('type');
        if($type == 1101){
            $downloadUrl = Config::getValue('downloadUrl');
        }else{
            $downloadUrl = Config::getValue('ios_download_url');
        }
        return $this->ajax(1,'请求成功',['downloadUrl'=>$downloadUrl]);
    }

    public function getConfig(Request $request){
        $key = trim($request->input('key'),',');
        $param = explode(',',$key);
        $data = [];
        foreach ($param as $value){
            $config = Config::getValue($value);
            if($value == 'versionCode'){
                $data[$value] = intval($config);
            }else{
                $data[$value] = $config;
            }
        }
        return $this->ajax(1,'请求成功',$data);
    }
}
