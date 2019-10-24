<?php 
namespace app\api\service;

use app\libs\exception\OrderException;
use app\libs\exception\UserException;
use app\libs\enum\OrderStatusEnum;
use think\Exception;

class Order {
	protected $oProducts;
	protected $uid;
	protected $products;

    /**
     * @param int $uid 用户id
     * @param array $oProducts 订单商品列表
     * @return array 订单商品状态
     * @throws Exception
     */
	public function place($uid, $oProducts){
		$this->oProducts = $oProducts;
		$this->uid = $uid;
		$this->products = $this->getProductsByOrder($oProducts);
		$status = $this->getOrderStatus();//判断是否能够下订单
		if(!$status['pass']){
			$status['order_id'] = -1;
            return $status;
		}

		//生产订单快照并写入数据库进行下单
		$orderSnap = $this->snapOrder($status);
		$status = $this->createOrderByTrans($orderSnap);
		$status['pass'] = true;
		return $status;
	}

    // 根据订单查找真实商品
    private function getProductsByOrder($oProducts)
    {
        $oPIDs = [];
        foreach ($oProducts as $item) {
            array_push($oPIDs, $item['product_id']);
        }
        // 为了避免循环查询数据库(先将product_id组合为数组进行批量查询)
        $products = Model('Product')::all($oPIDs)
             ->visible(['id', 'price', 'stock', 'name', 'main_img_url']) //过滤保留的数据
             ->toArray();
        return $products;
    }

    /**
     * 判断能否下单
     * a）库存是否充足
     * b）客户端下单的产品是否服务器上面匹配得到
     */
    private function getOrderStatus(){
    	$status = [
    		'pass' => true,
    		'orderPrice' => 0,
    		'totalCount' => 0,
    		'pStatusArray' => []
    	];
    	foreach($this->oProducts as $oProduct){
    		$pStatus = $this->getProductStatus($oProduct['product_id'], $oProduct['count']);
    		if(!$pStatus['haveStock']){
    			$status['pass'] = false;
    		}
    		array_push($status['pStatusArray'], $pStatus);
    		$status['orderPrice'] += $pStatus['totalPrice'];
    		$status['totalCount'] += $pStatus['count'];
    	}
    	return $status;
    }
    // 细分各项产品的信息
    private function getProductStatus($oPID, $oCount){
    	$pIndex = -1;
    	$pStatus = [
    		'id' => null,
    		'haveStock' => false,
    		'count' => 0,
    		'name' => '',
    		'totalPrice' => 0,
    		'main_img_url' => null
    	];
    	for($i=0; $i < count($this->products); $i++){
    		if($oPID == $this->products[$i]['id']){
    			$pIndex = $i;
    		}
    	}
    	if($pIndex == -1){
    		// 客户端传递的product_id有可能根本不存在
            throw new OrderException(
            	[
                    'msg' => 'id为' . $oPID . '的商品不存在，订单创建失败'
                ]);
    	}else {
    		$product = $this->products[$pIndex];
    		$pStatus['id'] = $product['id'];
    		$pStatus['count'] = $oCount;
    		$pStatus['name'] = $product['name'];
    		$pStatus['main_img_url'] = $product['main_img_url'];
    		$pStatus['totalPrice'] = $product['price'] * $oCount;
    		if($product['stock']-$oCount>=0){
    			$pStatus['haveStock'] = true;
    		}
    	}
    	return $pStatus;
    }


    // 预检测并生成订单快照
    private function snapOrder($status){
    	$snap = [
    		'orderPrice' => 0,
            'totalCount' => 0,
            'pStatus' => [],
    		'snapImg' => $this->products[0]['main_img_url'],
    		'snapName' => $this->products[0]['name'],
    		'snapAddress' => json_encode($this->getUserAddress())
    	];
    	if(count($this->products) > 1){
    		$snap['snapName'] .= '等';
    	}

    	$snap['orderPrice'] = $status['orderPrice'];
    	$snap['totalCount'] = $status['totalCount'];
    	$snap['pStatus'] = $status['pStatusArray'];

    	return $snap;
    }

    private function getUserAddress(){
    	$userAddress = Model('UserAddress')->getAddressInfoByUID($this->uid);
    	if (!$userAddress) {
            throw new UserException(
                [
                    'msg' => '用户收货地址不存在，下单失败',
                    'errorCode' => 60001,
                ]);
        }
        return $userAddress->toArray();
    }

    //下单
    private function createOrderByTrans($orderSnap){
    	try{
    		$orderNo = $this->makeOrderNo();
    		$data = [
    			'order_no' => $orderNo,
    			'user_id' => $this->uid,
    			'total_price' => $orderSnap['orderPrice'],
    			'snap_img' => $orderSnap['snapImg'],
    			'snap_name' => $orderSnap['snapName'],
    			'total_count' => $orderSnap['totalCount'],
    			'snap_items' => json_encode($orderSnap['pStatus']),
    			'snap_address' => $orderSnap['snapAddress']
    		];
    		$order= Model('Order')->newOrder($data);
    		foreach($this->oProducts as &$p){
    			$p['order_id'] = $order->id;
    		}
    		$orderProduct = Model('OrderProduct')->newOrderProductBatch($this->oProducts);

    		return [
    			'order_id' => $order->id,
    			'order_no' => $orderNo,
    			'create_time' => $order->create_time
    		];
    	}catch(Exception $ex){
    		throw $ex;
    	}
    	exit();
    }

    private function makeOrderNo(){
    	$yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2019] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }

    /**
     * @param string $orderNo 订单号
     * @return array 订单商品状态
     * @throws Exception
     */
    public function checkOrderStock($orderId){
        $oProducts = Model('OrderProduct')->where('order_id', $orderId)->select();
        $this->products = $this->getProductsByOrder($oProducts);
        $this->oProducts = $oProducts;
        $status = $this->getOrderStatus();   
        return $status;
    }

    //cms 发货
    public function delivery($orderId, $jumpPage = ''){
        $order = Model('Order')::find($orderId);
        if(!$order){
            throw new OrderException();
        }
        if($order->status != OrderStatusEnum::PAID){
            throw new OrderException([
                'msg' => '订单状态异常，操作失败',
                'errorCode' => 80002,
                'code' => 403
            ]);
        }
        //修改订单状态
        $order->status = OrderStatusEnum::DELIVERED;
        $order->save();
        //发货通知
        $message = new DeliveryMessage();
        return $message->sendDeliveryMessage($order, $jumpPage);
    }

}