<?php

namespace app\admin\controller\general;

use app\admin\model\Admin;
use app\common\controller\Backend;
use fast\Random;
use think\Session;
use think\Validate;

/**
 * 个人配置
 *
 * @icon fa fa-user
 */
class Profile extends Backend
{

    public function _initialize()
    {
        parent::_initialize();
        //新加
        $this->authgroup =new \app\admin\model\AuthGroup;
        $issuper=$this->auth->isSuperAdmin();
        $this->view->assign("issuper", $issuper);
        
        //获取公司数组
        $sgroup=$this->auth->getGroups();  
        $ginfo= collection($this->authgroup
            ->where('pid','in',$sgroup[0]['id'])
            ->select())->toArray();
        $comdata = [
           0 => __('None')
        ];
        foreach ($ginfo as $k => &$v)
        {

            $comdata[$v['id']] = $v['name'];
        }
        $this->view->assign("super_comlist", $comdata);
    }
    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            $model = model('AdminLog');
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $total = $model
                ->where($where)
                ->where('admin_id', $this->auth->id)
                ->order($sort, $order)
                ->count();

            $list = $model
                ->where($where)
                ->where('admin_id', $this->auth->id)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 更新个人信息
     */
    public function update()
    {
        if ($this->request->isPost()) {
            $this->token();
            $params = $this->request->post("row/a");
            $comid=$params['comid'];
            $params = array_filter(array_intersect_key(
                $params,
                array_flip(array('email', 'nickname', 'password', 'avatar'))
            ));
            unset($v);
            $params['comid']=$comid;
          
            if (!Validate::is($params['email'], "email")) {
                $this->error(__("Please input correct email"));
            }
            if (isset($params['password'])) {
                if (!Validate::is($params['password'], "/^[\S]{6,16}$/")) {
                    $this->error(__("Please input correct password"));
                }
                $params['salt'] = Random::alnum();
                $params['password'] = md5(md5($params['password']) . $params['salt']);
            }
            $exist = Admin::where('email', $params['email'])->where('id', '<>', $this->auth->id)->find();
            if ($exist) {
                $this->error(__("Email already exists"));
            }
            if ($params) {
                $admin = Admin::get($this->auth->id);
                $admin->save($params);
                //因为个人资料面板读取的Session显示，修改自己资料后同时更新Session
                Session::set("admin", $admin->toArray());
                $this->success();
            }
            $this->error();
        }
        return;
    }
}
