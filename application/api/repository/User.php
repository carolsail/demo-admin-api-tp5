<?php
namespace app\api\repository;

use app\common\model\User as UserModel;

class User
{
    public function getRowById($id)
    {
        return UserModel::get($id);
    }

    public function check($username, $password)
    {
        return UserModel::where(['username'=>$username, 'password'=>md5_trim_pwd($password)])->find();
    }

    public function checkSalt($id, $salt)
    {
        return UserModel::where(['id'=>$id, 'salt'=>$salt])->find();
    }

    public function edit($data)
    {
        $row = UserModel::get($data['id']);
        if ($row) {
            $data['avatar'] = empty($data['avatar']) ? '' : $data['avatar'][0];
            $row->allowField(true)->force()->save($data);
        }
    }
}
