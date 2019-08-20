<?php

namespace app\admin\controller\zhibocms\zbjk;

use app\common\controller\Backend;

/**
 * 直播会议管理
 *
 * @icon fa fa-circle-o
 */
class Hylist extends Backend
{
    
    /**
     * Hylist模型对象
     * @var \app\admin\model\zhibocms\zbjk\Hylist
     */
    protected $model = null;

    protected $searchFields = 'channel.channel_name';  //搜索
    
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\zhibocms\zbjk\Hylist;
       
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    

    /**
     * 查看
     */
    public function index()
    {
        
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
           
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
         
           
            //未来七天,过去六个小时
            $map=[
                'start_time'=>[
                    'between',
                    [strtotime('-6 hours'),strtotime('+7 days')]
                ]
            ];
            $total = $this->model
                    ->with(['mcu','channel'])
                    ->where($where)
                    ->where($map)
                    ->order($sort, $order)

                    ->count();

            $list = $this->model
                    ->with(['mcu','channel'])
                    ->where($where)
                    ->where($map)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                $row->visible(['id','cloud_id','starttime_text','stoptime_text','channel.channel_name']);
                
            }
            
            $list = collection($list)->toArray();
           
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
    
  
}
