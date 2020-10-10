<?php

namespace app\zoom\controller;

use think\Controller;
use think\Db;
class Index extends Base
{
    private $zoomid;
    private $webexid;
    private $password;
    private $rtmpconfig;
    private $channerid;
    private $db; //数据库
    private $prefix;
    public function _initialize() {
        $this->db=Db::connect('zbsql');
        $this->prefix=config('zbsql.prefix');
        
            $this->zoomid= session('zoomid')?session('zoomid'):$this->needlogin();
            $this->webexid=session('webexid')?session('webexid'):$this->needlogin();
            $this->password=session('password')?session('password'):$this->needlogin();
            $this->rtmpconfig=session('rtmpconfig')?session('rtmpconfig'):$this->needlogin();
            $this->channerid=session('channerid')?session('channerid'):$this->needlogin();
    }
   
     /*
     * 返回需要登陆信息
     */
    private function needlogin(){
        $this->error('账号或密码不正确或失效',url('/zoomlogin'));
    }

    public function index($type=1)
    {
        $time=time();
        if($type==1){  //未开始
            $map=[
                'start_time'=>['gt',$time],
                'delete_time'=>['gt',$time],
                'channel_id'=> $this->channerid
            ];
        }else{   //进行中
            $map=[
                'start_time'=>['elt',$time],
                'delete_time'=>['gt',$time],
                'channel_id'=> $this->channerid
            ];
        }
        $list=$this->db->name('meeting_log')
                     ->where($map)
                     ->select();
        
        $this->assign('type', $type);
        $this->assign('list', $list);
     
        return $this->fetch('list');
    }
    
    public function add() {
        if(request()->isPost()){
            $meetingName= input('post.meetingName')?trim(input('post.meetingName')):date('Y/m/d H:i').'的直播会议';
            $meetingStart=input('post.meetingStart');
            $meetduration=input('post.meetduration');
//            $meetingPassword=input('post.meetingPassword')?input('post.meetingPassword'):'';
            //查询会议时间是否冲突
            $starttime=strtotime($meetingStart);
            $stoptime=strtotime($meetingStart)+$meetduration*60;
            $pullstarttime=strtotime($meetingStart)-2*60;
            $timedata=[
                'startime'=>$pullstarttime,
                'stoptime'=>$stoptime,
            ];
            $room_id=$this->validateMeeting($timedata);  //验证并获取会议室号
            //获取呼叫号
            $zoomdata=$this->db->name('zoom_list')->where(['id'=> $this->zoomid])->find();
            if(empty($zoomdata)){
                $this->error('系统错误，请重新登录',url('/zoomlogin'));
            }
            if(!empty($zoomdata['callnum']) && !empty($zoomdata['calladdress'])){
                $cloudstr=$zoomdata['callnum'].'@'.$zoomdata['calladdress'];
            }else{
                $this->error('您的zoom未录入呼叫号，请联系管理员录入');
            }
            $postdata=[
                'mcu_id'=>$room_id,
                'hyname'=>$meetingName,
                'start_time'=>$starttime,
                'stop_time'=>$stoptime,
                'delete_time'=>$stoptime,
                'channel_id'=> $this->channerid,
                'cloud_id'=>$cloudstr,
                'meetingkey'=>$zoomdata['callnum']
            ];
            
            $log_id=$this->db->name('meeting_log')->insertGetId($postdata);
            $insertdata=[
                'channel_id'=>$this->channerid,
                'meeting_id'=>$zoomdata['callnum'], //使用zoom的呼叫会议id
                'log_id'=>$log_id,
                'call_work'=>2  //代表mcu
            ];
            Db::connect('zbsql')->name('c_meeting')->insert($insertdata);
            $queuedata=[
                'mcu_id'=>$room_id,
                'startime'=>$pullstarttime,
                'stoptime'=>$stoptime,
                'meetingkey'=>$zoomdata['callnum'],
                'sipurl'=>$cloudstr,
                'rtmpurl'=> $this->rtmpconfig,
                'name'=>$meetingName,
                'is_live'=>1,
                'log_id'=>$log_id ,  //主要判断字段
                'is_pull'=>0,//是否添加拉流
                'pull_url'=>'',
                'calltype'=>'h323'
            ];
          
            $this->pullqueue('app\common\jobs\McuCall@sendlive', $queuedata, 'mcucall');
            
          
            
            //加入删除队列
            $queuertmpdata=[
                'meetingkey'=>$zoomdata['callnum'],
                'stoptime'=>$stoptime,
                'startime'=>$pullstarttime,
                'log_id'=>$log_id,   //主要判断字段
                'type'=>1 //取消会议类型 1为创建会议加入的取消队列，2为直接取消会议取消队列
            ];
            $this->pullqueue('app\common\jobs\McuStop@sendlive', $queuertmpdata, 'mcustop');
            $data=[
                'meetid'=>$log_id
                ];
            $this->success('预约成功',null,$data);
            
            
        }
        return $this->fetch();
    }
    
    public function begin() {
        return $this->fetch();
    }
 //验证会议是否冲突
    protected function validateMeeting($timedata) {
        
        //先查询该频道下是否已经冲突时段的直播会议
        $c_where=[
            'channel_id'=> $this->channerid,
            'start_time'=>['elt',$timedata['stoptime']],
            'delete_time'=>[
                ['egt',$timedata['startime']],
                ['egt',time()],
                ]
        ];

        $count=$this->db->name('meeting_log')
                ->where($c_where)
                ->count();
        if($count){
            $this->error('该频道在您预约的时间段内已有别会议，请重新选择时间。');
           
        }
        //临时添加周末周日维护
       
//        if(1577635200>$timedata['stoptime']||$timedata['startime']>1546358399){
//                
//        }else{
//            echo $this->buildFailed(-1, '平台将于2019/12/30 00:00~2020/01/01 23:59期间进行网络维护,请重新选择时间。',[],false); 
//                
//                exit;
//        }
    
        //查询当前会议室列表
        $m_list=$this->db->name('mcu_list')
                ->field('id')
                ->select();
        
        //查询是否有空闲的会议室(把时间冲突的会议室id查询出来)
        $where=[
            
            'start_time'=>['elt',$timedata['stoptime']],
            'delete_time'=>[
                ['egt',$timedata['startime']],
                ['egt',time()],
                ]
        ];
        $wedata=$this->db->name('meeting_log')
                ->distinct(true)
                ->field('mcu_id')
                ->where($where)
                ->select();
        if(empty($wedata)&&!empty($m_list)){
            
            return $m_list[0]['id'];
        }else{
            foreach ($wedata as $key => $value) {
                foreach ($m_list as $kk => $vv) {
                   if($value['mcu_id']==$vv['id']){
                       unset($m_list[$kk]);  
                   } 
                }
            }
            if(!empty($m_list)){
                foreach ($m_list as $key => $value) {
                    $n_m_list[]=$value;
                }
                return $n_m_list[0]['id'];
            }else{ 
                $this->error('无空闲的会议室供您创建会议，请重新选择时间。');
                
            }
        }
        

    }
 
    
    
    public function details() {
        
         $logid= input('meetid/d');
         if($logid){
             $where=[
                 'ml.id'=>$logid,
                 'ml.channel_id'=>$this->channerid
             ];
             $info=$this->db->name('meeting_log')->alias('ml')
                     ->join($this->prefix.'channel c','c.id=ml.channel_id')
                     ->where($where)
                     ->field('ml.*,c.channel_name,c.pushurl,c.play_url')
                     ->find();
             if(!empty($info)){
                 
             
             $this->assign('info',$info);
             return $this->fetch();
             }
         }
        
    }
    
    
    public function delete() {
        $logid= input('meetid/d');
        if($logid){
            
        
         $where=[
                 'id'=>$logid,
                 'channel_id'=>$this->channerid
             ];
         $update=[
             'delete_time'=>time()
         ];
         $this->db->name('meeting_log')
                 ->where($where)
                 ->update($update);
         
        }
        $quedata=[
            'log_id'=>$logid,
            'type'=>2
            ];
        $this->pullqueue('app\common\jobs\McuStop@sendlive', $quedata, 'mcustop');    
        $this->success('取消成功');
    }

    private function pullqueue($action,$data,$job){
        $jobdata=[
            'job'=>$action,
            'data'=>$data
        ];
        
        $jobstr=json_encode($jobdata);
        $insert=[
            'queue'=>$job,
            'payload'=>$jobstr,
            'attempts'=>0,
            'reserved'=>0,
            'available_at'=>time(),
            'created_at'=>time()
        ];
        
        $this->db->name('manage_job')->insert($insert);
    }
}
