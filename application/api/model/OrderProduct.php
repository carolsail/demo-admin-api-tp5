<?php

namespace app\api\model;

class OrderProduct extends BaseModel {

	public function newOrderProductBatch($arr){
		return self::saveAll($arr);
	}
}