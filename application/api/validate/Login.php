<?php

namespace app\api\validate;

class Login extends BaseValidate {
	
	protected $rule = [
		'username' => 'require|isNotEmpty',
		'password' => 'require|isNotEmpty'
	];
	
}