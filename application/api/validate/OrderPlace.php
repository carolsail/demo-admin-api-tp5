<?php

namespace app\api\validate;

use app\libs\exception\ParameterException;

class OrderPlace extends BaseValidate {
	//对数组的验证，进行拆分
    protected $rule = [
        'products' => 'checkProducts'
    ];

    protected $singleRule = [
        'product_id' => 'require|isInt',
        'count' => 'require|isInt',
    ];

    //规则里面调用的函数不能private
    protected function checkProducts($values)
    {
        if(empty($values)){
            throw new ParameterException([
                'msg' => '商品列表不能为空'
            ]);
        }
        foreach ($values as $value)
        {
            $this->checkProduct($value);
        }
        return true;
    }

	private function checkProduct($value){
		$validate = new BaseValidate($this->singleRule);//重新new一个验证器
		$result = $validate->check($value);//调用验证器的check方法进行规则验证
		if(!$result){
            throw new ParameterException([
                'msg' => '商品列表参数错误',
            ]);
        }
	}
}