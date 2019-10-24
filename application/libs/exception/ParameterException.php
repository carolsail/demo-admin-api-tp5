<?php

namespace app\libs\exception;

class ParameterException extends BaseException {
	public $code = 400;
    public $errorCode = 10000;
    public $msg = "invalid parameters";
}