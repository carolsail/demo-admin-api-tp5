<?php

namespace app\api\model;

class ProductImage extends BaseModel {
	protected $hidden = ['img_id', 'product_id', 'delete_time', 'update_time'];

	public function img() {
		return $this->belongsTo('Image', 'img_id', 'id');
	}
}