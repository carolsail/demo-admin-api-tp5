<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\Login;
use app\api\service\User as UserService;
use app\api\service\Token;
use app\api\exception\ParameterException;

class User extends BaseController
{
    public function login()
    {
        (new Login())->goCheck();
        $token = (new UserService())->createToken();
        return json(['token'=>$token]);
    }

    public function logout()
    {
        $res = Token::tokenClear();
        return json($res);
    }

    /**
     * 验证token是否存在
     */
    public function verify($token = '')
    {
        if (!$token) {
            throw new ParameterException();
        }
        $valid = Token::verifyToken($token);
        return json(['isValid' => $valid]);
    }

    /**
     * 获取当前token用户登录信息
     */
    public function current()
    {
        $keys = ['username', 'name', 'avatar', 'email', 'mobile', 'scope'];
        $res = Token::getCurrentIdentity($keys);
        return json($res);
    }
}
