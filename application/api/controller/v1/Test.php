<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\exception\ParameterException;
use \Firebase\JWT\JWT;
use libs\Random;

class Test extends BaseController
{
    public function random()
    {
        //echo time().'+++'.$_SERVER['REQUEST_TIME_FLOAT'];
        echo Random::alnum(32);
    }

    public function auth()
    {
        $key = config('setting.token_salt');
        $token = [
          'iat' => time(),
          'exp' => time() + 30,
          'data' => [
            'id' => 1,
            'username' => 'carolsail'
          ]
        ];
        echo JWT::encode($token, $key);
    }

    public function verify()
    {
        $key = config('setting.token_salt');
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1NzI5NDAxMjUsImV4cCI6MTU3Mjk0MDE1NSwiZGF0YSI6eyJpZCI6MSwidXNlcm5hbWUiOiJjYXJvbHNhaWwifX0.pSBG9jIuNTAb8meLcosjMcxwd-zFxkykR768ZOKQzJc";
        try {
            JWT::$leeway = 60;
            $decoded = JWT::decode($token, $key, ['HS256']);
            $arr = (array)$decoded;
            print_r($arr['data']);
        } catch (\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
            echo 111;
            echo $e->getMessage();
        } catch (\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
            echo $e->getMessage();
        } catch (\Firebase\JWT\ExpiredException $e) {  // token过期
            echo 333;
            echo $e->getMessage();
        } catch (\Exception $e) {  //其他错误
            echo 444;
            echo $e->getMessage();
        }
    }
}
