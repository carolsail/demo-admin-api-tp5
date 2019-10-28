<?php

namespace app\api\exception;

class ForbiddenException extends BaseException
{
    public $code = 403;
    public $errorCode = 10002;
    public $msg = '权限有误';
}
