<?php

namespace app\api\exception;

class TokenException extends BaseException
{
    public $code = 401;
    public $errorCode = 10003;
    public $msg = 'Token已过期或无效Token';
}
