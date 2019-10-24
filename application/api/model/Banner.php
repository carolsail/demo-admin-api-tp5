<?php

namespace app\api\model;

class Banner extends BaseModel{

	public function items(){
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }

	public function getBannerById($id){
		return self::with(['items', 'items.img'])->find($id);
	}	
}