<?php

namespace app\api\model;

class Product extends BaseModel {
	protected $hidden = ['delete_time', 'update_time', 'create_time', 'main_img_id', 'pivot', 'from', 'category_id', 'img_id'];

	public function imgs() {
		return $this->hasMany('ProductImage', 'product_id', 'id');
	}

	public function properties() {
		return $this->hasMany('ProductProperty', 'product_id', 'id');
	}

	public function getMainImgUrlAttr($value, $data) {
		return $this->prefixImgUrl($value, $data);
	}

	public function getProductsByCategoryID($categoryId, $paginate = true, $page = 1, $size = 30){
		$query = self::where('category_id', $categoryId);
		if($paginate){
			$products = $query->paginate($size, true, ['page' => $page]);
		}else{
			$products = $query->all();
		}	
		return $products;
	}

	public function getProductDetailById($id){
		return self::with(['imgs' => function($query){
			$query->with('img')->order('order','asc');
		}, 'properties'])->find($id);
	}

	public function getRecentProducts($count){
		return self::limit($count)->order('create_time','desc')->all();
	}
}