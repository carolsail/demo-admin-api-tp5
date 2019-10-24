<?php

namespace app\api\service;

use think\Exception;
use app\libs\exception\OrderException;
use app\libs\exception\TokenException;
use think\facade\Log;
//加载微信官方类库
require '../extend/WxPay/WxPay.Api.php';

class Pay {
	private $orderNo;
    private $orderID;

	public function __construct($orderID){
		if (!$orderID)
        {
            throw new Exception('订单号不允许为NULL');
        }
		$this->orderID = $orderID;
	}

	public function pay(){
		//判断订单状态:1.订单是否与用户匹配, 2.订单是否已支付 
		$this->checkOrderValid();
		//判断能否下单
		$order = new Order();
		$status = $order->checkOrderStock($this->orderID);
		if(!$status['pass']){
			return $status; //['pass' => false,'orderPrice' => 0,'totalCount' => 0,'pStatusArray' => []]
		}
		return $this->makeWxPreOrder($status['orderPrice']);
	}

	private function checkOrderValid(){
		$order = Model('Order')->find($this->orderID);
		if(!$order){
			throw new OrderException();
		}

		if(!Token::isValidOperate($order->user_id)){
			throw new TokenException(
                [
                    'msg' => '订单与用户不匹配',
                    'errorCode' => 10003
                ]);
		}

		if($order->status != 1){
			throw new OrderException([
                'msg' => '订单已支付过啦',
                'errorCode' => 80003,
                'code' => 400
            ]);
		}
		$this->orderNo = $order->order_no;
		return true;
	}

	// 构建微信支付订单信息
	private function makeWxPreOrder($totalPrice){
		$openid = Token::getCurrentTokenVar('openid');
		if (!$openid)
        {
            throw new TokenException();
        }
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNo);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice * 100);
        $wxOrderData->SetBody('零食商贩');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url(config('secure.pay_back_url'));

        return $this->getPaySignature($wxOrderData);
	}

	// 向微信请求订单号并生成签名
	private function getPaySignature($wxOrderData){
		$wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
		if($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] !='SUCCESS'){
            Log::record($wxOrder,'error');
            Log::record('获取预支付订单失败','error');
            return $wxOrder;
        }

        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;
	}

	// 修改数据库中的prepay_id
	private function recordPreOrder($wxOrder){
		// 必须是update，每次用户取消支付后再次对同一订单支付，prepay_id是不同的(后续根据prepay_id进行信息推送等)
		Model('Order')::where('id', $this->orderID)
            ->update(['prepay_id' => $wxOrder['prepay_id']]);
	}

	// 签名
	private function sign($wxOrder){
		$jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        $rand = md5(time() . mt_rand(0, 1000));
        $jsApiPayData->SetNonceStr($rand);
        $jsApiPayData->SetPackage('prepay_id=' . $wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');
        $sign = $jsApiPayData->MakeSign();
        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;
        unset($rawValues['appId']);
        return $rawValues;
	}
}