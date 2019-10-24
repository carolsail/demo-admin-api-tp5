<?php

namespace app\api\model;

class ThirdApp extends BaseModel {

	public function check($ac, $se){
		//se数据库中进行了加密
		$se_md5 = md5($se.config('secure.encryption_key'));
		return self::where(['app_id'=>$ac, 'app_secret'=>$se_md5])->find();
	}
}