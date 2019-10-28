<?php
namespace app\api\service;

use app\api\repository\Account as AccountRepository;
use app\api\exception\ParameterException;

class Account extends Token
{
    public function createToken()
    {
        $data = input('post.');
        if (!(isset($data['account']) && isset($data['password']))) {
            throw new ParameterException();
        }
        $row = (new AccountRepository)->check($data['account'], $data['password']);
        if (!$row) {
            throw new ParameterException(['msg'=>'账号或密码有误']);
        }
        $account = [
            'id' => $row->id,
            'account' => $row->account,
            'avatar' => $row->avatar,
            'name' => $row->name,
            'email' => $row->email,
            'scope' => $row->scope
        ];
        return $this->saveToCache($account);
    }

    private function saveToCache($info)
    {
        $token = self::generateToken();
        $expire_in = config('setting.token_expire_in');
        // token为key的缓存数据
        $res = cache($token, json_encode($info), $expire_in);
        if (!$res) {
            throw new ParameterException(['msg' => '服务器缓存异常']);
        }
        return $token;
    }
}
