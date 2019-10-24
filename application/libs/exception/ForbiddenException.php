<?php

namespace app\libs\exception;

class ProductException extends BaseException {
    public $code = 403;
    public $msg = '权限不够';
    public $errorCode = 10001;
}