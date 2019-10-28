<?php
namespace app\api\traits;

use app\api\exception\ParameterException;
use app\api\exception\MissException;
use app\api\exception\SuccessMessage;
use app\api\validate\IDMustBeInt;
use app\api\validate\BatchDelIdStr;

trait Curd
{
    private function isExtend($fun="index")
    {
        $flag = false;
        if (isset($this->repository)) {
            $flag = method_exists($this->repository, $fun) ? true : false;
        }
        return $flag;
    }

    /**
     * method: get
     *
     * sort: id
     * order: desc
     * offset: 0
     * limit: 10
     * filter: {'name': 'test'}
     * op: {'name': 'like'}
     */
    public function index()
    {
        list($where, $sort, $order, $offset, $limit) = $this->buildparams();
        $total = $this->model->where($where)->count();
        $lists = $this->model->where($where)->order($sort, $order)->limit($offset, $limit)->select();

        if ($this->isExtend('index')) {
            $lists = $this->repository->index($lists);
        }
        $result = array("total" => $total, "rows" => $lists);
        return json($result);
    }

    public function create()
    {
        $params = input('post.');
        if ($params) {
            if ($this->validator) {
                $this->validator->goCheck($params);
            }
            if ($this->isExtend('create')) {
                $insertId = $this->repository->create($params);
            } else {
                $this->model->allowField(true)->save($params);
                $insertId = $this->model->id;
            }
            throw new SuccessMessage(['msg'=>'操作成功']);
        } else {
            throw new ParameterException();
        }
    }

    public function edit()
    {
        $params = input('post.');
        if ($params) {
            if (isset($this->validate)) {
                $this->validate->goCheck($params);
            }
            if ($this->isExtend('edit')) {
                $this->repository->edit($params);
            } else {
                $row = $this->model->get($params['id']);
                if (!$row) {
                    throw new ParameterException();
                }
                $row->allowField(true)->force()->save($params);
            }
            throw new SuccessMessage(['msg'=>'操作成功']);
        } else {
            throw new ParameterException();
        }
    }

    //  .../delete?ids=1,2,3
    public function delete($ids='')
    {
        if ($ids) {
            (new BatchDelIdStr())->goCheck();
            if ($this->isExtend('delete')) {
                $this->repository->delete($ids);
            } else {
                $this->model->destroy($ids);
            }
            throw new SuccessMessage(['msg'=>'操作成功']);
        } else {
            throw new ParameterException();
        }
    }

    // .../info?id=1
    public function info($id)
    {
        (new IDMustBeInt())->goCheck();
        $row = $this->model->find($id);
        if (!$row) {
            throw new MissException();
        }
        return $row;
    }
}
