<?php

namespace app\admin\controller\meetingroom\txl;

use app\common\controller\MeetBackend;
use fast\Tree;
use think\Db;
/**
 * 会议模式
 *
 * @icon fa fa-circle-o
 */
class TxlType extends MeetBackend
{
    
    /**
     * Txltype模型对象
     * @var \app\admin\model\meetingroom\txl\Txltype
     */
    protected $model = null;
    protected $rulelist = [];
    protected $parent_id;
    protected $authgroup;

    public function _initialize()
    {
        parent::_initialize();
      
        
       
        
        $this->model = new \app\admin\model\meetingroom\txl\Txltype;
        $this->txlmodel = new \app\admin\model\meetingroom\txl\Txl;
      
        
        $this->view->assign("statusList", $this->model->getStatusList());
      
      
        $mapwhere=[
        'uid'=>$this->parent_id
        ];

     
        
        $ruleList = collection($this->model->where($mapwhere)->order('weigh', 'desc')->select())->toArray();
        Tree::instance()->init($ruleList);
        $this->rulelist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'title');
        $ruledata = [0 => __('None')];
        foreach ($this->rulelist as $k => &$v)
        {
           
            $ruledata[$v['id']] = $v['title'];
        }
        $this->view->assign('ruledata', $ruledata);
        
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
        if ($this->request->isAjax())
        {
            $list = $this->rulelist;
            $total = count($this->rulelist);

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }


    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids)
        {
            $delIds = [];
            foreach (explode(',', $ids) as $k => $v)
            {
                $delIds = array_merge($delIds, Tree::instance()->getChildrenIds($v, TRUE));
            }
            $delIds = array_unique($delIds);
            
            $txlcount = $this->txlmodel->where('pid', 'in', $delIds)->count();
            if($txlcount){
                $this->error('分组下包含数据，请确认在回收站里彻底删除');
            }
            $where=[
                 'uid'=> $this->parent_id,
                 'id'=>['in',$delIds]
            ];
            $count = $this->model->where($where)->delete();
            if ($count)
            {
                $this->success();
            }
        }
        $this->error();
    }
}
