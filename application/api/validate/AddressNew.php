<?php
namespace app\api\validate;

class AddressNew extends BaseValidate
{
	protected $rule = [
		'name' => 'require|isNotEmpty',
		'province' => 'require|isNotEmpty',
		'city' => 'require|isNotEmpty',
		'country' => 'require|isNotEmpty',
		'mobile' => 'require|isNotEmpty',
		'detail' => 'require|isNotEmpty'
	];

	protected $message = [
		'name' => 'name必填',
		'province' => 'province必填',
		'city' => 'city必填',
		'country' => 'country必填',
		'mobile.require' => '电话必填',
		'detail' => 'detail必填'
	];
}