<?php
namespace app\api\repository;
use app\api\model\Banner as BannerModel;

class Banner
{
    public function create($data)
    {
      $model = new BannerModel();
      $data['img'] = empty($data['img']) ? '' : $data['img'][0];
      $model->allowField(true)->save($data);
      return $model->id;
    }

    public function edit($data)
    {
      $row = BannerModel::get($data['id']);
      if($row) {
        $data['img'] = empty($data['img']) ? '' : $data['img'][0];
        $row->allowField(true)->force()->save($data);
      }
    }
}
