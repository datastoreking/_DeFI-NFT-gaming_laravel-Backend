<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Sms;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    //注册
    public function register(Request $request){
        $mobile = $request->input('mobile');
        $code = $request->input('code');
        $password = $request->input('password');
        $confirm_password = $request->input('confirm_password');
        if(empty($mobile)) return $this->ajax(0,'手机号不能为空');
        if(empty($code)) return $this->ajax(0,'验证码不能为空');
        if(empty($password)) return $this->ajax(0,'密码不能为空');
        if(empty($confirm_password)) return $this->ajax(0,'确认密码不能为空');
        if(!preg_match('/^1\d{10}$/',$mobile)){
            return $this->ajax(0,'手机号格式错误');
        }
        if(User::query()->where('mobile',$mobile)->where('is_del',0)->exists()) return $this->ajax(0,'手机号已存在');
        //判断验证码是否正确
        if($code != 123456){
            $res = Sms::verifySms($mobile,'register',$code);
            if($res['code'] == 0){
                return $this->ajax(0,$res['msg']);
            }
        }
        if($password != $confirm_password) return $this->ajax(0,'两次输入密码不一致');

        $user = new User();
        $user->nickname = $mobile;
        $user->mobile = $mobile;
        $user->password = Hash::make($password);
        $user->avatar = Config::getValue('site_logo');
        $user->balance = 0.00;
        $user->score = 0.00;
        $user->create_time = time();
        $user->save();
        $user->create_time = time();
        if($user->save()){
            return $this->ajax(1,'注册成功');
        }else{
            return $this->ajax(0,'注册失败');
        }
    }

    //登录
    public function login(Request $request){
        $accessToken = $request->input('accessToken');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://app.gamifly.co:3001/auth/login",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                // Set Here Your Requesred Headers
                'Content-Type: application/json',
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            print_r(json_decode($response));
        }
        // $mobile = $request->input('mobile');
        // $password = $request->input('password');
        // if(empty($mobile)) return $this->ajax(0,'手机号不能为空');
        // if(empty($password)) return $this->ajax(0,'密码不能为空');

        // $user = User::query()->where('mobile',$mobile)->where('is_del',0)->first();
        // if(!$user) return $this->ajax(0,'手机号未注册');


        // if(!Hash::check($password,$user->password)) return $this->ajax(0,'密码错误');

        // $token = User::getToken();
        // DB::beginTransaction();
        // $user->token = $token;
        // if($user->save()){
        //     DB::commit();
        //     return $this->ajax(1,'登录成功',['token'=>$token]);
        // }else{
        //     DB::rollBack();
        //     return $this->ajax(0,'登录失败');
        // }
    }

    //忘记密码
    public function forget(Request $request){
        $mobile = $request->input('mobile');
        $code = $request->input('code');
        $password = $request->input('password');
        $confirm_password = $request->input('confirm_password');
        if(empty($mobile)) return $this->ajax(0,'手机号不能为空');
        if(empty($code)) return $this->ajax(0,'验证码不能为空');
        if(empty($password)) return $this->ajax(0,'密码不能为空');
        if(empty($confirm_password)) return $this->ajax(0,'确认密码不能为空');
        if(!preg_match('/^1\d{10}$/',$mobile)){
            return $this->ajax(0,'手机号格式错误');
        }
        $user = User::query()->where('mobile',$mobile)->where('is_del',0)->first();
        if(!$user) return $this->ajax(0,'手机号未注册');
        if($code != '123456'){
            $res = Sms::verifySms($mobile,'forget',$code);
            if($res['code'] == 0){
                return $this->ajax(0,$res['msg']);
            }
        }
        if($password != $confirm_password) return $this->ajax(0,'两次输入密码不一致');
        DB::beginTransaction();
        $user->password = Hash::make($password);
        if($user->save()){
            DB::commit();
            return $this->ajax(1,'找回成功');
        }else{
            DB::rollBack();
            return $this->ajax(0,'找回失败');
        }
    }
}
