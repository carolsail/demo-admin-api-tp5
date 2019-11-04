<?php 
namespace app\api\service;

use app\api\exception\TokenException;
use app\api\exception\ForbiddenException;
use think\Exception;
use app\api\enum\ScopeEnum;

class Token {

	public static function generateToken(){
		$randChar = getRandChar(32); 
		$timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
		$tokenSalt = config('setting.token_salt');
		return md5($randChar . $timestamp . $tokenSalt);
	}

	public static function getCurrentTokenVar($key){
		$token = request()->header('token');
		$vars = cache($token);
		if(!$vars){
			throw new TokenException();
		}else{
			if(!is_array($vars))
            {
                $vars = json_decode($vars, true);
            }
            if(array_key_exists($key, $vars)){
            	return $vars[$key];
            }else{
				throw new TokenException(['msg'=>'尝试获取的Token变量并不存在']);
            }
		}
	}

	public static function getCurrentUid(){
		$uid = self::getCurrentTokenVar('uid');
		return $uid;
	}

	// cms专有权限
	public static function needSuperScope(){
		$scope = self::getCurrentTokenVar('scope');
		if($scope){
			if($scope==ScopeEnum::Super){
				return true;
			}else{
				throw new ForbiddenException();
			}
		}else{
			throw new TokenException();
		}
	}

	//用户以上权限
	public static function needPrimaryScope(){
		$scope = self::getCurrentTokenVar('scope');
		if($scope){
			if($scope >= ScopeEnum::User){
				return true;
			}else{
				throw new ForbiddenException();
			}
		}else{
			throw new TokenException();
		}
	}
 
	public static function verifyToken($token){
		$exist = cache($token);
		if($exist){
            return true;
        }
        else{
            return false;
        }
	}

    /**
     * 检查操作UID是否合法
     * @param $checkedUID
     * @return bool
     * @throws Exception
     * @throws ParameterException
     */
	public static function isValidOperate($checkedUID){
		if(!$checkedUID){
            throw new Exception('检查UID时必须传入一个被检查的UID');
        }
		$uid = self::getCurrentUid();
		if($uid == $checkedUID){
			return true;
		}
		return false;
	}
	
	/**
     * 从缓存中获取当前用户指定身份标识
     * @param array $keys
     * @return array result
     * @throws \app\lib\exception\TokenException
     */
    public static function getCurrentIdentity($keys)
    {
        $token = request()->header('token');
        $identities = cache($token);
        if (!$identities)
        {
            throw new TokenException();
        }
        else
        {
            $identities = json_decode($identities, true);
            $result = [];
            foreach ($keys as $key)
            {
                if (array_key_exists($key, $identities))
                {
                    $result[$key] = $identities[$key];
                }
            }
            return $result;
        }
	}
	
	/**	
	 * token 销毁
	 */
	public static function tokenClear(){
        $token = request()->header('token');
        if(cache($token)){
			cache($token, Null);
			return true;
		}else{
			throw new TokenException();
		}
	}
}
