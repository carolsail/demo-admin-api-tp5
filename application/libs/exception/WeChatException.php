<?php

namespace app\libs\exception;

class TokenException extends BaseException {
    public $code = 400;
    public $msg = 'wechat unknown error';
    public $errorCode = 999;
}