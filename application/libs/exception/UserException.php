<?php

namespace app\libs\exception;

class UserException extends BaseException {
    public $code = 404;
    public $msg = '用户不存在';
    public $errorCode = 60000;
}