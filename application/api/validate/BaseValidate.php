<?php
namespace app\api\validate;

use think\Validate;
use app\libs\exception\ParameterException;
use think\Exception;
class BaseValidate extends Validate
{

	public function goCheck(){
		$params = input('param.');

		if(!$this->check($params)){
			// $this->error有一个问题，并不是一定返回数组，需要判断
			$msg = is_array($this->error) ? implode(';', $this->error) : $this->error;
			throw new ParameterException(['msg'=>$msg]);
		}
		return true;
	}


	public function isInt($value, $rule='', $data='', $field=''){
		if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
		    return true;
		}
		return false;
	}

	public function isNotEmpty($value, $rule='', $data='', $field=''){
		if (empty($value)) {
            return false;
        }
        return true;
	}

	public function isMobile($value, $rule='', $data='', $field=''){
		$rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
	}
	

    /**
     * @param array $arrays 通常传入request.post变量数组
     * @return array 按照规则key过滤后的变量数组
     * @throws ParameterException
     */
    public function getDataByRule($arrays)
    {
        if (array_key_exists('user_id', $arrays) | array_key_exists('uid', $arrays)) {
            // 不允许包含user_id或者uid，防止恶意覆盖user_id外键
            throw new ParameterException([
                'msg' => '参数中包含有非法的参数名user_id或者uid'
            ]);
        }
        $newArray = [];
        foreach ($this->rule as $key => $value) {
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }
}