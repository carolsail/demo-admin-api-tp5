<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\IDMustBeInt;
use app\libs\exception\MissException;

class Banner extends BaseController
{
    public function getBanner($id)
    {
    	(new IDMustBeInt())->goCheck();
    	$banner = Model('Banner')->getBannerById($id);
    	if(!$banner){
    		throw new MissException([
                'msg' => '请求banner不存在',
                'errorCode' => 40000
            ]);
    	}
    	return $banner;   
    }
}
