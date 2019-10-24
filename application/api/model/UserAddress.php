<?php

namespace app\api\model;

class UserAddress extends BaseModel{

	public function getAddressInfoByUID($uid) {
		return self::where('user_id', $uid)->find();
	}
}