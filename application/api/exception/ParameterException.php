<?php

namespace app\api\exception;

class ParameterException extends BaseException {
	public $code = 400;
    public $errorCode = 10000;
    public $msg = "参数不合法";
}