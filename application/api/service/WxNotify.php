<?php

namespace app\api\service;

use app\api\service\Order as OrderService;
use think\Db;
use think\facade\Log;
use think\Exception;
use app\libs\enum\OrderStatusEnum;

//引入微信官方类库
require '../extend/WxPay/WxPay.Api.php';

class WxNotify extends \WxPayNotify{

	//重构方法
	public function NotifyProcess($data, &$msg){
        if ($data['result_code'] == 'SUCCESS') {
            $orderNo = $data['out_trade_no'];
            Db::startTrans();
            try {
                $order = Model('Order')::where('order_no', $orderNo)->find();
                if ($order->status == 1) {
                    $service = new OrderService();
                    $status = $service->checkOrderStock($order->id);
                    if ($status['pass']) {
                        $this->updateOrderStatus($order->id, true);
                        $this->reduceStock($status);
                    } else {
                        $this->updateOrderStatus($order->id, false);
                    }
                }
                Db::commit();
            } catch (Exception $ex) {
                Db::rollback();
                Log::error($ex);
                // 如果出现异常，向微信返回false，请求重新发送通知
                return false;
            }
        }
        return true;
	}

	private function updateOrderStatus($orderID, $success){
		$status = $success ? OrderStatusEnum::PAID : OrderStatusEnum::PAID_BUT_OUT_OF;
		Model('Order')->where('id', $orderID)->update(['status' => $status]);
	}

	private function reduceStock($status){
		foreach ($status['pStatusArray'] as $singlePStatus) {
            Model('Product')::where('id', '=', $singlePStatus['id'])
                ->setDec('stock', $singlePStatus['count']);
        }
	}
}