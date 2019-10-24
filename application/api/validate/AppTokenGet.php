<?php

namespace app\api\validate;

class AppTokenGet extends BaseValidate {
	
	protected $rule = [
		'ac' => 'require|isNotEmpty',
		'se' => 'require|isNotEmpty'
	];

	protected $message = [
		'ac.require' => 'account必须',
		'ac.isNotEmpty' => 'account不能为空',
		'se.require' => 'password必须',
		'se.isNotEmpty' => 'password不能为空'
	];
}