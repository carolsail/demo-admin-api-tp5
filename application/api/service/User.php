<?php
namespace app\api\service;

use app\api\repository\User as UserRepository;
use app\api\exception\ParameterException;

class User extends Token
{
    public function createToken()
    {
        $data = input('post.');
        if (!(isset($data['username']) && isset($data['password']))) {
            throw new ParameterException();
        }
        $row = (new UserRepository)->check($data['username'], $data['password']);
        if (!$row) {
            throw new ParameterException(['msg'=>'账号或密码有误']);
        }
        $data = [
            'id' => $row->id,
            'username' => $row->username,
            'avatar' => $row->avatar,
            'name' => $row->name,
            'email' => $row->email,
            'scope' => $row->scope
        ];
        return $this->saveToCache($data);
    }

    private function saveToCache($data)
    {
        $token = self::generateToken();
        $expire_in = config('setting.token_expire_in');
        // token为key的缓存数据
        $res = cache($token, json_encode($data), $expire_in);
        if (!$res) {
            throw new ParameterException(['msg' => '服务器缓存异常']);
        }
        return $token;
    }
}
