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

    //登录 - NewAPI1
    public function login(Request $request){
        $accessToken = $request->input('accessToken'); 
        $params=['accessToken'=>$accessToken];
        $ch = \curl_init('https://app.gamifly.co:3001/auth/login');
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $response = \curl_exec($ch);
        \curl_close($ch);
        $result = json_decode($response,true);

        $data = [];
        $data['id'] = $result['id'];
        $data['nickname'] = $result['name'];
        $data['avatar'] = $result['avatar'];
        $data['mobile'] = '';
        $data['password'] = '';
        $data['balance'] = $result['balance'];
        $data['score'] = '';
        $data['token'] = $result['access_token'];
        $data['create_time'] = time();
        $data['state'] = '';
        $data['is_del'] = 0;
        $data['achieve'] = 0;
        $data['platform_wallet'] = $result['platform_wallet'];
        $data['email'] = $result['email'];
        $data['external_wallet_address'] = $result['external_wallet_address'];
        $data['bitcoin_address'] = $result['bitcoin_address'];
        $data['sol_address'] = $result['sol_address'];
        $data['login_type'] = $result['login_type'];
        $data['verified'] = $result['verified'];
        $data['referral_id'] = $result['referral_id'];
        $data['ip'] = $result['ip'];

        $getcurrentIdcount = User::query()->where('id',$result['id'])->count();
        if($getcurrentIdcount == 0){
            DB::table('user')->insert($data);
        } else {
            User::where('id', $result['id'])->delete();
            DB::table('user')->insert($data);
        } 
        return($result);
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
