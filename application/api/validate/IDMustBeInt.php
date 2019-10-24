<?php
namespace app\api\validate;

class IDMustBeInt extends BaseValidate
{
	protected $rule = [
		'id' => 'require|isInt'
	];

	protected $message = [
		'id.require' => 'id必须',
		'id.isInt' => 'id必须为正整数'
	];
}