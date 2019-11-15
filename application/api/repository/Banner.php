<?php
namespace app\api\repository;

use app\common\model\Banner as BannerModel;

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
        if ($row) {
            $data['img'] = empty($data['img']) ? '' : $data['img'][0];
            $row->allowField(true)->force()->save($data);
        }
    }

    public function getExportRows($where)
    {
        $rows = BannerModel::where($where)->select();
        return $rows;
    }
}
