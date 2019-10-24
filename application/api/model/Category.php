<?php

namespace app\api\model;

class Category extends BaseModel{
	protected $hidden = ['topic_img_id', 'delete_time', 'update_time'];

	public function topicImg(){
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

    public function products(){
    	return $this->hasMany('Product', 'category_id', 'id');
    }

    public function getCategories($ids){
    	return self::with(['topicImg', 'products'])->all($ids);
    }
}