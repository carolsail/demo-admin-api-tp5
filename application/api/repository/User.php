<?php
namespace app\api\repository;

class User {

    public function getRowById($id){
        return model('user')->find($id);
    }

    public function check($username, $password){
        return model('user')->where(['username'=>$username, 'password'=>md5_trim_pwd($password)])->find();
    }

    public function checkSalt($id, $salt){
        return model('user')->where(['id'=>$id, 'salt'=>$salt])->find();
    }
}