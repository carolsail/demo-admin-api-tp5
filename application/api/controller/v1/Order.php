<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\PagingParameter;
use app\api\validate\OrderPlace;
use app\api\validate\IDMustBeInt;
use app\api\service\Token;
use app\api\service\Order as OrderService;
use app\libs\exception\SuccessMessage;


class Order extends BaseController
{
    protected $beforeActionList = [
        'checkSuperScope' => ['only' => 'getSummary,delivery'],
        'checkPrimaryScope' => ['only' => 'getSummaryByUser'],
        'checkExclusiveScope' => ['only' => 'placeOrder']
    ];

    //cms调用订单列表
    public function getSummary($page=1, $size=20)
    {
        $back = [
            'current_page' => $page,
            'data' => []
        ];
    	(new PagingParameter())->goCheck();
        $orders = Model('Order')->getSummaryByPage($page,$size);
    	if(!$orders->isEmpty()){
           $data = $orders->hidden(['snap_items', 'snap_address'])->toArray();  
           $back['data'] = $data['data'];
        }
    	return json($back);   
    }


    /**
     * 小程序下单
     */
    public function placeOrder(){
        (new OrderPlace())->goCheck();
        $uid = Token::getCurrentUid();
        $products = input('post.products/a');
        $status = (new OrderService())->place($uid, $products);
        return json($status);
    }

    /**
     * (小程序)根据用户id分页获取订单列表（简要信息）
     * @param int $page
     * @param int $size
     * @return array
     * @throws \app\lib\exception\ParameterException
     */
    public function getSummaryByUser($page=1, $size=15){
        (new PagingParameter())->goCheck();
        $back = [
            'current_page' => $page,
            'data' => []
        ];
        $uid = Token::getCurrentUid();
        $orders = Model('Order')->getSummaryByUser($uid, $page, $size);
        if(!$orders->isEmpty()){
           $data = $orders->hidden(['snap_items', 'snap_address'])->toArray();  
           $back['data'] = $data['data'];
        }
        return json($back);
    }


    public function delivery($id){
        (new IDMustBeInt())->goCheck();
        $order = new OrderService();
        $success = $order->delivery($id);
        if($success){
            throw new SuccessMessage();
        }
    }

}
