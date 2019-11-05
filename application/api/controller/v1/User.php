<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\Login;
use app\api\service\User as UserService;
use app\api\service\Token;
use app\api\exception\ParameterException;

class User extends BaseController
{
    /**
     * 登录操作
     * 获取token
     */
    public function login()
    {
        (new Login())->goCheck();
        $token = (new UserService())->getToken();
        return json(['token'=>$token]);
    }

    /**
     * 验证token
     * 返回当前token用户登录信息
     */
    public function verify($token = '')
    {
        $res = UserService::verifyToken();
        return json($res);
    }
}
