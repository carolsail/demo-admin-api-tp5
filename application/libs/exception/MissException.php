<?php

namespace app\libs\exception;

class MissException extends BaseException {
	public $code = 404;
    public $errorCode = 10001;
    public $msg = "global:your required resource are not found";
}