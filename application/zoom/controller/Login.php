<?php

namespace app\zoom\controller;

use think\Controller;
use think\Db;
class Login extends Controller
{

    protected $db; //数据库
    protected $prefix;
    public function _initialize() {
       
        $this->db=Db::connect('zbsql');
        $this->prefix=config('zbsql.prefix');
    }
   

    public function login()
    {
           $this->assign('re_webExId',cookie('re_webExId'));
            $this->assign('re_password',cookie('re_password'));
           
            return $this->fetch();
    }

    public function postLogin(){
          $webexid= trim(input('post.webExId'));
            $password=input('post.password')?input('post.password'):'';
            $remember=input('post.remember');
            
            $wemap=[
            'zm.zoomname'=>$webexid,
            'zm.zoompwd'=>$password,
        ];
        $wedata=$this->db->name('zoom_list')->alias('zm')
                ->join($this->prefix.'channel c','zm.clid = c.id','left')
                ->where($wemap)
                ->field('zm.*,c.channel_name,c.pushurl')
                ->find();
   
        if(empty($wedata)){
            
            $this->error('该账号您无权限登陆或者未绑定您的频道');
          
        }
        
        session('zoomid',$wedata['id']);
        session('webexid',$webexid);
        session('password',$password);
        session('rtmpconfig',$wedata['pushurl']);
        session('channerid',$wedata['clid']);
             
                    
        if($remember=='on'){
            cookie('re_webExId',$webexid);
            cookie('re_password',$password);
         }else{
            cookie('re_webExId',null);
            cookie('re_password',null); 
         }
              
           
      $this->success('登陆成功');        
    }

}
