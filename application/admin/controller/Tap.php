<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use app\common\logic\AclAppLogic;
/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Tap extends Backend
{
    
    /**
     * Tap模型对象
     * @var \app\admin\model\Tap
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Tap;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    
    
  public function index()
    {
      
      //模拟提交数据
      $data=[
          'aclname'=>'AIHUA',
           'ploylist'=>[                    //策略列表是个二维数组，可以包含多个数组(策略)
               [    
                //第一个策略
                   'id'=>10,                 //id：可选，默认为空
                   'pattern'=>'permit',      //允许还是禁止:可选，默认permit
                   'protocol'=>'ip',         //协议：可选，默认ip
                   'sourceAddr'=>'1.1.1.0',  //源地址：必填
                   'destAddr'=>'2.2.2.2',    //目的地址：必填
               ]
          ]
      ];
      $acl=AclAppLogic::getInstance();
      $str=$acl->addAclPloy([],$data);
      
      print_r($str);exit;
      
        //设置过滤方法
//        $this->request->filter(['strip_tags']);
//        if ($this->request->isAjax()) {
//            //如果发送的来源是Selectpage，则转发到Selectpage
//            if ($this->request->request('keyField')) {
//                return $this->selectpage();
//            }
//            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
//            $total = $this->model
//                ->where($where)
//                ->order($sort, $order)
//                ->count();
//
//            $list = $this->model
//                ->where($where)
//                ->order($sort, $order)
//                ->limit($offset, $limit)
//                ->select();
//
//            $list = collection($list)->toArray();
//            $result = array("total" => $total, "rows" => $list);
//
//            return json($result);
//        }
//        return $this->view->fetch();
    }

}
