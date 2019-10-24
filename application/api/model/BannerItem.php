<?php

namespace app\api\model;

class BannerItem extends BaseModel{
	protected $hidden = ['id', 'img_id', 'banner_id', 'delete_time', 'update_time'];

	public function img(){
		//一对一关联关系：所在表中包含外键id的时候使用belongsTo，否则使用hasOne
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}