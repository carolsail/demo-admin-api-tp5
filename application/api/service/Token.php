<?php 
namespace app\api\service;

use app\libs\exception\TokenException;
use app\libs\exception\ForbiddenException;
use think\Exception;
use app\libs\enum\ScopeEnum;

class Token {

	public static function generateToken(){
		$randChar = getRandChar(32); 
		$timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
		$tokenSalt = config('secure.token_salt');
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
				throw new Exception('尝试获取的Token变量并不存在');
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

	// 用户（小程序）专有权限
	public static function needExclusiveScope(){
		$scope = self::getCurrentTokenVar('scope');
		if($scope){
			if($scope == ScopeEnum::User){
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
	
}
