<?php

namespace app\api\service;

use think\Exception;

class AccessToken {

    private $tokenUrl;
    const TOKEN_CACHED_KEY = 'access';
    const TOKEN_EXPIRE_IN = 7000;

    function __construct()
    {
        $url = config('wx.access_token_url');
        $url = sprintf($url, config('wx.app_id'), config('wx.app_secret'));
        $this->tokenUrl = $url;
    }

    // 建议用户规模小时每次直接去微信服务器取最新的token
    // 但微信access_token接口获取是有限制的 2000次/天
	public function get(){
		$token = $this->getFromCache();
		if(!$token){
            return $this->getFromWxServer();
        }
        else{
            return $token;
        }
	}

	//本地缓存中获取
	private function getFromCache(){
		$token = cache(self::TOKEN_CACHED_KEY);
		if($token){
			return $token['access_token'];
		}
		return null;
	}

	//微信服务器中获取
	private function getFromWxServer(){
        $token = curl_get($this->tokenUrl);
        $token = json_decode($token, true);
        if (!$token)
        {
            throw new Exception('获取AccessToken异常');
        }
        if(!empty($token['errcode'])){
            throw new Exception($token['errmsg']);
        }
        $this->saveToCache($token); // ['access_token'=>'***', 'expires_in'=>7200]
        return $token['access_token'];
	}

	private function saveToCache($token){
		cache(self::TOKEN_CACHED_KEY, $token, self::TOKEN_EXPIRE_IN);
	}

}