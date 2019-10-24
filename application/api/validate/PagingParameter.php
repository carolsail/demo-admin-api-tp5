<?php

namespace app\api\validate;

class PagingParameter extends BaseValidate {
	protected $rule = [
		'page' => 'isInt',
		'size' => 'isInt'
	];

	protected $message = [
		'page.isInt' => 'page必须为正整数',
		'size.isInt' => 'size必须为正整数'
	];
}