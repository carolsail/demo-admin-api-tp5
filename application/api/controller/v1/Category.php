<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\IDMustBeInt;
use app\libs\exception\MissException;

class Category extends BaseController
{
    public function getAllCategories()
    {
    	$categories = Model('Category')->getCategories([]);
    	if($categories->isEmpty()){
    		throw new MissException([
                'msg' => '还没有任何类目',
                'errorCode' => 50000
            ]);
    	}
    	return $categories;   
    }
}
