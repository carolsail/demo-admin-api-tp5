<?php

namespace app\api\model;

class User extends BaseModel{

	public function address(){
        return $this->hasOne('UserAddress', 'user_id', 'id');
    }

	public function getByOpenID($openid){
		return self::where('openid', $openid)->find();
	}	

	public function newByOpenID($openid){
		$user = self::create(['openid'=>$openid]);
		return $user->id;
	}

	public function getAddressInfo($id){
		return self::with(['address'])->find($id);
	}
}