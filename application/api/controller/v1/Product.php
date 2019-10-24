<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\IDMustBeInt;
use app\api\validate\Count;
use app\api\validate\PagingParameter;
use app\libs\exception\ProductException;

class Product extends BaseController
{
    /**
     * 根据类目ID获取该类目下所有商品(分页）
     * @url /product/by_category/paginate?id=:category_id&page=:page&size=:page_size
     * @param int $id 商品id
     * @param int $page 分页页数（可选)
     * @param int $size 每页数目(可选)
     * @return array of Product
     * @throws ParameterException
     */
    public function getByCategory($id=-1, $page=1, $size=30){
        (new IDMustBeInt())->goCheck();
        (new PagingParameter())->goCheck();
        
        $products = Model('Product')->getProductsByCategoryID($id, true, $page, $size);
        if($products->isEmpty()){
            // 对于分页最好不要抛出MissException，客户端并不好处理
            $back = [
                'current_page' => $products->currentPage(),
                'data' => []
            ];
            return json($back);
        }
        $back = [
            'current_page' => $products->currentPage(),
            'data' => $products->hidden(['summary'])
        ];
        return json($back);
    }

    /**
     * 获取某分类下全部商品(不分页）
     * @url /product/by_category?id=:category_id
     * @param int $id 分类id号
     * @return \think\Paginator
     * @throws ThemeException
     */
    public function getAllInCategory($id = -1){
        (new IDMustBeInt())->goCheck();
        $products = Model('Product')->getProductsByCategoryID($id, false);
        if($products->isEmpty()){
            throw new ProductException();
        }
        return $products;
    }

    public function getOne($id){
        (new IDMustBeInt())->goCheck();
        $product = Model('Product')->getProductDetailById($id);
        if(!$product){
            throw new ProductException();
        }
        return $product;
    }

    /**
     * 获取指定数量的最近商品
     * @url /product/recent?count=:count
     * @param int $count
     * @return mixed
     * @throws ParameterException
     */
    public function getRecent($count = 15){
        (new Count())->goCheck();
        $products = Model('Product')->getRecentProducts($count);
        if(empty($products)){
            throw new ProductException();
        }
        return $products->hidden(['summary']);
    }
}
