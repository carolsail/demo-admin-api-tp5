<?php

namespace app\api\service;

use app\libs\exception\TokenException;
use app\libs\exception\WeChatException;
use think\Exception;
use app\libs\enum\ScopeEnum;

class UserToken extends Token {

	protected $code;
	protected $wxAppID;
	protected $wxAppSecret;
	protected $wxLoginUrl;

	function __construct($code){
		$this->code = $code;
		$this->wxAppID = config('wx.app_id');
		$this->wxAppSecret = config('wx.app_secret');
		$this->wxLoginUrl = sprintf(config('wx.login_url'), $this->wxAppID, $this->wxAppSecret, $this->code);
	}

	public function getToken(){
		//去微信服务器获取openid && session_key 
		// {"session_key":"EuZuKtZqNfGrhtqijrdRdQ==","openid":"o3wEQ5RaSq2NyPgpCdK_PM-ezL3w"}
		$res = curl_get($this->wxLoginUrl);
		$wxRes = json_decode($res, true);
		if(empty($wxRes)){
			throw new Exception('获取session_key及openID时异常，微信内部错误');
		}else{
			// 微信服务器并不会将错误标记为400，无论成功还是失败都标记成200
            // 这样非常不好判断，只能使用errcode是否存在来判断
            $loginFail = array_key_exists('errcode', $wxRes);
            if ($loginFail) {
                $this->processLoginError($wxRes);
            }
            else {
                return $this->grantToken($wxRes);
            }
		}
	}

	private function processLoginError($wxResult)
    {
        throw new WeChatException(
            [
                'msg' => $wxResult['errmsg'],
                'errorCode' => $wxResult['errcode']
            ]);
    }

    // 颁发令牌
    // 只要调用登陆就颁发新令牌
    // 但旧的令牌依然可以使用
    // 所以通常令牌的有效时间比较短
    // 目前微信的express_in时间是7200秒
    // 在不设置刷新令牌（refresh_token）的情况下
    // 只能延迟自有token的过期时间超过7200秒（目前还无法确定，在express_in时间到期后
    // 还能否进行微信支付
    // 没有刷新令牌会有一个问题，就是用户的操作有可能会被突然中断
    private function grantToken($wxResult){
    	$openid = $wxResult['openid'];
    	//判断数据库中是否已经存入openid，若没有则存入,最后返回用户数据库自己的id
    	$user = Model('User')->getByOpenID($openid);
    	if($user){
    		$uid = $user->id;
    	}else{
    		$uid = Model('User')->newByOpenID($openid);
    	}
    	$cachedValue = $this->prepareCachedValue($wxResult, $uid);
    	$token = $this->saveToCache($cachedValue);
    	return $token;
    }

    private function prepareCachedValue($wxResult, $uid){
    	$cachedValue = $wxResult;
    	$cachedValue['uid'] = $uid;
    	$cachedValue['scope'] = ScopeEnum::User;//wechat权限
    	return $cachedValue;
    }

    private function saveToCache($cachedValue){
    	$token = self::generateToken();
    	$expire_in = config('setting.token_expire_in');
    	$result = cache($token, json_encode($cachedValue), $expire_in);
    	if (!$result){
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
    	return $token;
    }

}