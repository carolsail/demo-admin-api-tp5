<?php
namespace app\api\validate;

class BatchDelIdStr extends BaseValidate
{
	protected $rule = [
		'ids' => 'require|checkBatchDelIdStr'
	];

	protected $message = [
		'ids.require' => 'ids必须',
		'ids.checkBatchDelIdStr' => 'ids格式有误'
    ];
    
    protected function checkBatchDelIdStr($value, $rule, $data){
        $arr = explode(',', $value);
        foreach($arr as $v){
            if(!$this->isInt($v)){
                return false;
            }
            return true;
        }
    }
}