<?php
namespace app\api\validate;

class Count extends BaseValidate
{
	protected $rule = [
		'count' => 'isInt|between:1,15'
	];

	protected $message = [
		'count.isInt' => 'count必须为正整数',
		'count.between' => 'count取值为1~15间的正整数'
	];
}