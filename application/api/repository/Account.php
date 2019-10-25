<?php
namespace app\api\repository;

class Account {
    public function check($account, $password){
        return model('account')->where(['account'=>$account, 'password'=>md5_trim_pwd($password)])->find();
    }
}