<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;

class Banner extends BaseController
{

    public function initialize()
    {
        parent::initialize();
    }

    //前置操作
    protected $beforeActionList = [
        'checkSuperScope'
    ];
}
