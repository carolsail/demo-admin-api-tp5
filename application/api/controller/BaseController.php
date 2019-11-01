<?php

namespace app\api\controller;

use think\Controller;
use app\api\service\Token;
use app\api\traits\Curd;

class BaseController extends Controller
{
    //默认增删改查等系列接口
    use Curd;
    public $model;
    public $repository;
    public $service;
    public $validator;
    public function initialize()
    {
        //初始化各模块
        $controller_name = substr($this->request->controller(), 3);//版本号截掉
        $model = "\\app\\api\\model\\".$controller_name;
        $repository = "\\api\\share\\repository\\".$controller_name;
        $service = "\\app\\api\\service\\".$controller_name;
        $validator = "\\app\\api\\validator\\".$controller_name;
        $this->model = class_exists($model) ? new $model : null;
        $this->repository = class_exists($repository) ? new $repository : null;
        $this->service = class_exists($service) ? new $service : null;
        $this->validator = class_exists($validator) ? new $validator : null;
    }
    
    //检查cms权限
    public function checkSuperScope()
    {
        Token::needSuperScope();
    }
    
    //检查用户级别以上权限
    public function checkPrimaryScope()
    {
        Token::needPrimaryScope();
    }
  

    /**
    * 生成查询所需要的条件,排序方式
    * 參數autoSql代表是否開啟自動where
    */
    protected function buildparams($autoSql = true)
    {
        $filter = $this->request->get("filter");
        $op = $this->request->get("op", '', 'trim');
        $sort = $this->request->get("sort", "id");
        $order = $this->request->get("order", "DESC");
        $offset = $this->request->get("offset", 0);
        $limit = $this->request->get("limit", 0);

        $filter = (array)json_decode($filter, true);
        $op = (array)json_decode($op, true);
        $filter = $filter ? $filter : [];
        $where = [];

        foreach ($filter as $k => $v) {
            $sym = isset($op[$k]) ? $op[$k] : '=';
            $v = !is_array($v) ? trim($v) : $v;
            $sym = strtoupper(isset($op[$k]) ? $op[$k] : $sym);
            switch ($sym) {
              case '=':
              case '!=':
                  $where[] = [$k, $sym, (string)$v];
                  break;
              case 'LIKE':
              case 'NOT LIKE':
              case 'LIKE %...%':
              case 'NOT LIKE %...%':
                  $where[] = [$k, trim(str_replace('%...%', '', $sym)), "%{$v}%"];
                  break;
              case '>':
              case '>=':
              case '<':
              case '<=':
                  $where[] = [$k, $sym, intval($v)];
                  break;
              case 'FINDIN':
              case 'FINDINSET':
              case 'FIND_IN_SET':
                  $where[] = "FIND_IN_SET('{$v}', " . ($relationSearch ? $k : '`' . str_replace('.', '`.`', $k) . '`') . ")";
                  break;
              case 'IN':
              case 'IN(...)':
              case 'NOT IN':
              case 'NOT IN(...)':
                  $where[] = [$k, str_replace('(...)', '', $sym), is_array($v) ? $v : explode(',', $v)];
                  break;
              case 'BETWEEN':
              case 'NOT BETWEEN':
                  $arr = array_slice(explode(',', $v), 0, 2);
                  if (stripos($v, ',') === false || !array_filter($arr)) {
                      continue;
                  }
                  //当出现一边为空时改变操作符
                  if ($arr[0] === '') {
                      $sym = $sym == 'BETWEEN' ? '<=' : '>';
                      $arr = $arr[1];
                  } elseif ($arr[1] === '') {
                      $sym = $sym == 'BETWEEN' ? '>=' : '<';
                      $arr = $arr[0];
                  }
                  $where[] = [$k, $sym, $arr];
                  break;
              case 'RANGE':
              case 'NOT RANGE':
                  if (!is_array($v)) {
                      $v = str_replace(' - ', ',', $v);
                      $arr = array_slice(explode(',', $v), 0, 2);
                      if (stripos($v, ',') === false || !array_filter($arr)) {
                          continue;
                      }
                  }else {
                      $arr = $v;
                      if(count($arr)==2 && ($arr[0]==$arr[1])){
                          $arr[1] = $arr[0] . ' 23:59:59';
                      }
                  }
                  //当出现一边为空时改变操作符
                  if ($arr[0] === '') {
                      $sym = $sym == 'RANGE' ? '<=' : '>';
                      $arr = $arr[1];
                  } elseif ($arr[1] === '') {
                      $sym = $sym == 'RANGE' ? '>=' : '<';
                      $arr = $arr[0];
                  }
                  $where[] = [$k, str_replace('RANGE', 'BETWEEN', $sym) . ' time', $arr];
                  break;
              case 'NULL':
              case 'IS NULL':
              case 'NOT NULL':
              case 'IS NOT NULL':
                  $where[] = [$k, strtolower(str_replace('IS ', '', $sym))];
                  break;
              default:
                  break;
          }
        }
        if ($autoSql) {
            $where = function ($query) use ($where) {
                foreach ($where as $k => $v) {
                    if (is_array($v)) {
                        call_user_func_array([$query, 'where'], $v);
                    } else {
                        $query->where($v);
                    }
                }
            };
        }
        return [$where, $sort, $order, $offset, $limit];
    }
}
