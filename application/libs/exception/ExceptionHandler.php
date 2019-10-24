<?php

namespace app\libs\exception;

use Exception;
use think\exception\Handle;
use think\facade\Log;

/*
 * 重写Handle的render方法，实现自定义异常消息
 */
class ExceptionHandler extends Handle{
	private $code;
    private $errorCode;
    private $msg;

    public function render(Exception $e)
    {
        
        if ($e instanceof BaseException) {
        	// 自定义异常
            $this->code = $e->code;
            $this->errorCode = $e->errorCode;
            $this->msg = $e->msg;
        } else {
        	//服务器异常
        	if(config('app.app_debug')){
				return parent::render($e);
			}

			$this->code = 500;
            $this->msg = 'sorry，we make a mistake. (^o^)Y';
            $this->errorCode = 999;
            //记录日志
			$this->recordErrorLog($e);
        }

        $result = [
            'msg'  => $this->msg,
            'error_code' => $this->errorCode,
            'request_url' => request()->url()
        ];
        return json($result, $this->code);
    }

    private function recordErrorLog(Exception $e){
    	Log::record($e->getMessage(),'error');
    }

}