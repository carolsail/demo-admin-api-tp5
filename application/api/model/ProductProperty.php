<?php

namespace app\api\model;

class ProductProperty extends BaseModel {
	protected $hidden=['product_id', 'id', 'delete_time', 'update_time'];
}