<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class BaseModel extends Model {
	use SoftDelete;
	protected $hidden = ['delete_time', 'update_time'];

	public function prefixImgUrl($value, $data){
		$url = $value;
		if($data['from']==1){
			$url = config('setting.img_prefix').$value;
		}
		return $url;
	}
}