<?php
namespace app\api\service;

use app\api\repository\User as UserRepository;
use app\api\exception\ParameterException;
use app\api\exception\TokenException;
use app\api\exception\ForbiddenException;
use app\api\enum\ScopeEnum;
use libs\Random;

class User extends Token
{
    // 获取token
    public function getToken()
    {
        $data = input('post.');
        if (!(isset($data['username']) && isset($data['password']))) {
            throw new ParameterException();
        }
        $row = (new UserRepository)->check($data['username'], $data['password']);
        if (!$row) {
            throw new ParameterException(['msg'=>'账号或密码有误']);
        }
        try {
            //数据库中加入局部盐salt
            $salt = Random::alnum(6);
            $row->salt = $salt;
            $row->save();
            $data = [
                'id' => $row->id,
                'username' => $row->username,
                'avatar' => $row->avatar,
                'name' => $row->name,
                'email' => $row->email,
                'scope' => $row->scope,
                'salt' => $salt
            ];
            return self::encoded($data, config('setting.token_expire_in'));
        } catch (\Exception $e) {
            throw new ParameterException(['msg' => $e->getMessage()]);
        }
    }
    
    // 验证token
    public static function verifyToken()
    {
        $data = self::decoded();
        $row = (new UserRepository)->checkSalt($data->id, $data->salt);
        if (!$row) {
            throw new TokenException();
        }
        return (array)$data;
    }

    // 刷新token
    public static function refreshToken() {
        $data = self::decoded();
        $row = (new UserRepository)->getRowById($data->id);
        try {
            // 刷新局部盐
            $salt = Random::alnum(6);
            $row->salt = $salt;
            $row->save();
            $data = [
                'id' => $row->id,
                'username' => $row->username,
                'avatar' => $row->avatar,
                'name' => $row->name,
                'email' => $row->email,
                'scope' => $row->scope,
                'salt' => $salt
            ];
            return self::encoded($data, config('setting.token_expire_in'));
        } catch (\Exception $e) {
            throw new ParameterException(['msg' => $e->getMessage()]);
        }
    }

    // cms专有权限
    public static function needSuperScope()
    {
        $user = self::verifyToken();
        $scope = $user['scope'];
        if ($scope) {
            if ($scope==ScopeEnum::Super) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }
  
    //用户以上权限
    public static function needPrimaryScope()
    {
        $user = self::verifyToken();
        $scope = $user['scope'];
        if ($scope) {
            if ($scope >= ScopeEnum::User) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }

    /** 
     * 修改局部盐，强制让指定用户下线
     */
    public function updateSalt($id)
    {
        $row = (new UserRepository)->getRowById($id);
        if($row){
            $row->salt = '';
            $row->save();
        }
    }
}
