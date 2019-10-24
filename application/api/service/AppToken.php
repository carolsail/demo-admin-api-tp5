<?php

namespace app\api\service;

use app\libs\exception\TokenException;

class AppToken extends Token {


	public function getToken($ac, $se){
		$row = Model('ThirdApp')->check($ac, $se);
		if($row){
			$info = [
				'id' => $row->id, //id
				'scope' => $row->scope //权限
			];
			return $this->saveToCache($info);
		}else{
			throw new TokenException([               
				'msg' => '授权失败',
                'errorCode' => 10004
            ]);
		}
	}

	private function saveToCache($info){
		$token = self::generateToken();
		$expire_in = config('setting.token_expire_in');
		$res = cache($token, json_encode($info), $expire_in); // token为key的缓存数据
		if(!$res){
			throw new TokenException([               
				'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
		}
		return $token;
	}

}