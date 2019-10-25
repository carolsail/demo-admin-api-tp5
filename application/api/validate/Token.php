<?php

namespace app\api\validate;

class Token extends BaseValidate {
	
	protected $rule = [
		'account' => 'require|isNotEmpty',
		'password' => 'require|isNotEmpty'
	];

	protected $message = [
		'account.require' => 'account必须',
		'account.isNotEmpty' => 'account不能为空',
		'password.require' => 'password必须',
		'password.isNotEmpty' => 'password不能为空'
	];
}