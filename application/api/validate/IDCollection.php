<?php
namespace app\api\validate;

class IDCollection extends BaseValidate
{
	protected $rule = [
		'ids' => 'require|checkIDs'
	];

	protected $message = [
		'ids.require' => 'id必须',
		'ids.checkIDs' => 'ids参数必须为以逗号分隔的多个正整数'
	];

	protected function checkIDs($value){
		$values = explode(',', $value);
		if(empty($values)){
			return false;
		}
		foreach($values as $v){
			if(!$this->isInt($v)){
				return false;
			}
		}
		return true;
	}
}