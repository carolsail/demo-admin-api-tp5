<?php

namespace app\api\controller;

use think\Controller;
use app\api\service\Token;

class BaseController extends Controller {

	//检查cms权限
	public function checkSuperScope(){
		Token::needSuperScope();
	}
	
	//检查用户级别以上权限
	public function checkPrimaryScope(){
		Token::needPrimaryScope();
	}

	//检查用户级别
	public function checkExclusiveScope(){
		Token::needExclusiveScope();
	}
}