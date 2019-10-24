<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\AppTokenGet;
use app\api\validate\TokenGet;
use app\api\service\Token as TokenService;
use app\api\service\AppToken;
use app\api\service\UserToken;
use app\libs\exception\ParameterException;

class Token extends BaseController {
	
	/**
     * 第三方应用获取令牌(cms)
     * @url /app_token?
     * @POST ac=:account se=:secret
     */
	public function getAppToken($ac='', $se=''){
		(new AppTokenGet())->goCheck();
		$token = (new AppToken())->getToken($ac, $se);
		return json(['token'=>$token]);
	}


	/**
	 * 用户获取令牌（wechat）
     * @url /token
     * @POST code
     * @note 虽然查询应该使用get，但为了稍微增强安全性，所以使用POST
	 */
	public function getToken($code = ''){
		(new TokenGet())->goCheck();
		$token = (new UserToken($code))->getToken();
		return json(['token'=>$token]);
	}	

	/**
	 * 验证token是否存在
	 */
	public function verifyToken($token = ''){
        if(!$token){
            throw new ParameterException([
                'token不允许为空'
            ]);
        }

        $valid = TokenService::verifyToken($token);
        return json(['isValid' => $valid]);
	}

}