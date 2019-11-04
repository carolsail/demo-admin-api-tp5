<?php
namespace app\api\repository;

class User {
    public function check($username, $password){
        return model('user')->where(['username'=>$username, 'password'=>md5_trim_pwd($password)])->find();
    }
}