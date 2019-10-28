<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\Token as TokenValidate;
use app\api\service\Account;
use app\api\service\Token as TokenService;
use app\api\exception\ParameterException;

class Token extends BaseController
{
    
    /**
     * 登录生成token
     * @url /app_token?
     * @POST account && password
     */
    public function get()
    {
        (new TokenValidate())->goCheck();
        $token = (new Account())->createToken();
        return json(['token'=>$token]);
    }

    /**
     * 注销
     */

    public function logout()
    {
        $res = TokenService::tokenClear();
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
        $valid = TokenService::verifyToken($token);
        return json(['isValid' => $valid]);
    }

    /**
     * 获取当前token用户登录信息
     */
    public function current()
    {
        $keys = ['account', 'name', 'avatar', 'email', 'mobile', 'scope'];
        $res = TokenService::getCurrentIdentity($keys);
        return json($res);
    }
}
