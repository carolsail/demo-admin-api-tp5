<?php

namespace app\api\exception;

class SuccessMessage extends BaseException {
    public $code = 201;
    public $msg = 'ok';
    public $errorCode = 0;
}