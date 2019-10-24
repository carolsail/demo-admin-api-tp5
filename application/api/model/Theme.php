<?php

namespace app\api\model;

class Theme extends BaseModel{

	protected $hidden = ['topic_img_id', 'head_img_id', 'delete_time', 'update_time'];

	public function topicImg(){
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

    public function headImg(){
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

    public function products(){
    	//多对多：参数多一个中间表
    	return $this->belongsToMany('Product', 'theme_product', 'product_id', 'theme_id');
    }

	public function getThemeList($ids){
		return self::with(['topicImg', 'headImg'])->all($ids);
	}	

	public function getThemeById($id){
		return self::with(['topicImg','headImg','products'])->find($id);
	}
}