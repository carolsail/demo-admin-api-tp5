<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\IDMustBeInt;
use app\api\service\Pay as PayService;
use app\api\service\WxNotify;

class Pay extends BaseController {

	protected $beforeActionList = [
		'checkExclusiveScope' => ['only' => 'getPreOrder']
	];

	public function getPreOrder($id = ''){
		(new IDMustBeInt())->goCheck();
		$pay = new PayService($id);
		$status = $pay->pay();
		return json($status);
	}

	public function receiveNotify(){
		$notify = new WxNotify();
        $notify->handle();
	}
}