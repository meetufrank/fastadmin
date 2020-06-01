<?php

namespace app\admin\controller\meetingroom\room;

use app\common\controller\MeetBackend;
use fast\Tree;
use think\Db;
/**
 * 会议模式
 *
 * @icon fa fa-circle-o
 */
class Roomtype extends MeetBackend
{
    
    /**
     * Txltype模型对象
     * @var \app\admin\model\meetingroom\txl\Txltype
     */
    protected $model = null;
    protected $rulelist = [];

    public function _initialize()
    {
        parent::_initialize();
      
        
       
        
        $this->model = new \app\admin\model\meetingroom\room\Roomtype;
        $this->datamodel = new \app\admin\model\meetingroom\room\Zdroom;
    
       
        
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
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }
                    $params['uid']= $this->parent_id;
                    $result = $this->model->allowField(true)->save($params);
                    Db::commit();
                } catch (\ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (\PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (\Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
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
            
            $txlcount = $this->datamodel->where('pid', 'in', $delIds)->count();
            if($txlcount){
                $this->error('分组下包含数据，请确认在回收站里彻底删除');
            }
       
            $count = $this->model->where('id', 'in', $delIds)->delete();
            if ($count)
            {
                $this->success();
            }
        }
        $this->error();
    }
}
