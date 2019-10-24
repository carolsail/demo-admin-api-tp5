<?php

namespace app\api\model;

class Order extends BaseModel{

	public function products(){
		return $this->belongsToMany('Product', 'order_product', 'product_id', 'order_id');
	}

	public function getSnapItemsAttr($value)
    {
        if(empty($value)){
            return null;
        }
        return json_decode($value);
    }

    public function getSnapAddressAttr($value){
        if(empty($value)){
            return null;
        }
        return json_decode(($value));
    }

	public function getSummaryByPage($page, $size){
		return self::order('create_time desc')->paginate($size, true, ['page' => $page]);
	}

	public function getSummaryByUser($uid, $page, $size){
		return self::where('user_id', $uid)->order('create_time desc')->paginate($size, true, ['page'=>$page]);
	}

	public function newOrder($arr){
		return self::create($arr);
	}
}