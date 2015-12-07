<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Curl\Curl;
use Validator;

class SmsController extends Controller
{
  public function getSmsCode(Request $request){
    $v = Validator::make($request->all(), [
        'phone' => 'required|regex:/[0-9]+/'
    ]);

    if ($v->fails())
    {
        return $this->response->error($v->errors(), 400);
    }

    $curl = new Curl();
    $curl->setHeader('Content-Type', 'application/json');
    $curl->setHeader('X-LC-Id', env('SMS_APPID', ''));
    $curl->setHeader('X-LC-Key', env('SMS_APPKEY', ''));

    $body = json_encode([
      'mobilePhoneNumber' => $request->query('phone'),
      'ttl' => 60
    ]);

    $curl->post('https://api.leancloud.cn/1.1/requestSmsCode', $body);

    return response()->json($curl->response);
  }


  public function registerByPhone(Request $request){
    $v = Validator::make($request->all(), [
        'phone' => 'required|regex:/[0-9]+/|unique:users,phone',
        'password' => 'required|between:6,32',
        'nickname' => 'required|between:1,16',
        'verification_code' => 'required|regex:/[0-9]+/'
    ]);

    if ($v->fails())
    {
      return $this->response->error($v->errors(), 400);
    }

    //验证短信验证码
    $curl = new Curl();
    $curl->setHeader('Content-Type', 'application/json');
    $curl->setHeader('X-LC-Id', env('SMS_APPID', ''));
    $curl->setHeader('X-LC-Key', env('SMS_APPKEY', ''));

    $curl->post('https://api.leancloud.cn/1.1/verifySmsCode/'.$request->input('verification_code').'?mobilePhoneNumber='.$request->input('phone'));
    // dd($curl->response);
    if (isset($curl->response->code)){
      return $this->response->error('无效的验证码', 400);
    }

    $user = new User;
    $user->phone = $request->input('phone');
    $user->nickname = $request->input('nickname');
    $user->password = Hash::make($request->input('password'));
    $user->save();

    return 'success';
  }

  public function test(Request $request){
    $curl = new Curl();

    $curl->post('http://tjz.frezc.com/auth', [
      'email' => '504021398@qq.com',
      'password' => 'secret'
    ]);

    return response()->json($curl->response);
  }
}