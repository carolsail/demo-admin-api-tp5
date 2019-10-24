<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\libs\exception\UserException;
use app\libs\exception\SuccessMessage;
use app\api\service\Token;
use app\api\validate\AddressNew;

class Address extends BaseController {

    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'getUserAddress']
    ];

    /**
     * 获取用户地址信息
     * @return UserAddress
     * @throws UserException
     */
	public function getUserAddress(){
		$uid = Token::getCurrentUid();
		$info = Model('User')->getAddressInfo($uid);
		if(!$info){
			throw new UserException([
               'msg' => '用户地址不存在',
               'errorCode' => 60001
            ]);
		}
		return $info;
	}

    /**
     * 更新或者创建用户收获地址
     */
	public function createOrUpdateAddress(){
		$validate = new AddressNew();
		$validate->goCheck();

		$uid = Token::getCurrentUid();
		$info = Model('User')->getAddressInfo($uid);
		if(!$info){
			throw new UserException([
                'code' => 404,
                'msg' => '用户收获地址不存在',
                'errorCode' => 60001
            ]);
		}

        // 根据规则取字段是很有必要的，防止恶意更新非客户端字段
        $data = $validate->getDataByRule(input('post.'));

		// 新增的save方法和更新的save方法并不一样
		// 新增的save来自于关联关系
		// 更新的save来自于模型
		if($info->address){
			//关联模型更新
			$info->address->save($data);
		}else{
			//关联模型添加
			$info->address()->save($data);
		}
		//抛出操作成功的信息（不局限于异常）
		throw new SuccessMessage();
	}
}