<?php
namespace app\api\service;

use app\api\exception\TokenException;
use \Firebase\JWT\JWT;

class Token
{
    //生成token
    public static function encoded($data, $exp=7200)
    {
        $key = config('setting.token_salt'); //全局盐
        $body = [
            'iat' => time(),
            'exp' => time() + $exp,
            'data' => $data
        ];
        return JWT::encode($body, $key);
    }
    //解析token
    public static function decoded()
    {
        $key = config('setting.token_salt');
        $token = request()->header('token');
        try {
            JWT::$leeway = 60;
            $decoded = JWT::decode($token, $key, ['HS256']);
            $arr = (array)$decoded;
            return $arr['data'];
        } catch (\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
            throw new TokenException(['msg'=>$e->getMessage()]);
        } catch (\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
            throw new TokenException(['msg'=>$e->getMessage()]);
        } catch (\Firebase\JWT\ExpiredException $e) {  // token过期
            throw new TokenException(['msg'=>$e->getMessage()]);
        } catch (\Exception $e) {  //其他错误
            throw new TokenException(['msg'=>$e->getMessage()]);
        }
    }
}
