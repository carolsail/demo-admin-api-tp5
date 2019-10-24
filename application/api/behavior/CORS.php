<?php

namespace app\api\behavior;

use think\Response;

class CORS{
	public function appInit($params) {
		header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: token,Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: POST,GET,PUT');
        //跨域复杂请求的时候options请求拦截
        if(request()->isOptions()){
        	exit();
        }
	}
}