<?php

namespace app\api\exception;

class MissException extends BaseException
{
    public $code = 404;
    public $errorCode = 10001;
    public $msg = "请求资源不存在";
}
