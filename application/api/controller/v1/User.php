<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\Login;
use app\api\service\User as UserService;
use app\api\service\Token;
use app\api\exception\ParameterException;

class User extends BaseController
{
    protected $beforeActionList = [
        'checkSuperScope' => ['except' => 'login,verify']
    ];

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
    public function verify()
    {
        $res = UserService::verifyToken();
        return json($res);
    }

    /**
     * 刷新token
     */
    public function refresh()
    {
        $token = UserService::refreshToken();
        return json(['token'=>$token]);
    }

    /**
     * 修改数据
     */
    public function change($type='password')
    {
        if ($type=='password') {
            UserService::changePassword(input('post.'));
        }
    }
}
