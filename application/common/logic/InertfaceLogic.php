<?php
namespace app\common\logic;


class InterfaceLogic extends Logic{
    
     /*
     * 声明接口或者对接口操作
     * @$arr   拼接数组
     * @$name  名称
     * 
     */
    public function loadInterface(array $arr=[],$name='',$oprate="") {
        
        
        //构成命令行数组
        $data=[
            $oprate,
            'interface',
            $name
           ];
        
        
  
        
         $arr[]=$this->format_arr($data);
        
         return $arr;
        
    }

    
    /*
     * 定义接口类型
     * @name 类型名称  tap/tool/tap-tool
     * 
     */
    public function configMode(array $arr=[],$name='') {
        
        
        //构成命令行数组
        $data=[
            'switchport',
            'mode',
            $name
           ];
        
        
  
        
         $arr[]=$this->format_arr($data);
        
         return $arr;
        
    }
    
    
    /*
     * 设定虚接口
     * @name 接口PC的编号
     * 
     */
    public function configPc(array $arr=[],$num='') {
        
        
        //构成命令行数组
        $data=[
            'channel-group',
            $num,
            'mode',
            'on'
           ];
        
        
  
        
         $arr[]=$this->format_arr($data);
        
         return $arr;
        
    }
    
    /*
     * 配置业务组
     * @$name   业务组名
     * @$interType  接口类型  tap/tool 如果是互联，则分别传参数调用这个函数
     */
   public function configGroup(array $arr=[],$name='',$interType='tap') {
        
       
       switch ($interType) {
           case 'tap':

               $wordstr='default';
               break;
           case 'tool':

               $wordstr='set';
               break;

           default:
               break;
       }
    
        
        //构成命令行数组
        $data=[
            'switchport ',
            $interType,
            $wordstr,
            'group',
            $name
           ];
        
        
  
        
         $arr[]=$this->format_arr($data);
        
         return $arr;
        
    }
    
    /*
     * 给接口打上标签 vlan
     */
    public function configVlan(array $arr=[],$interType='tap',$num='') {
        
         switch ($interType) {
           case 'tap':

               $wordstr=$num;
               break;
           case 'tool':

               $wordstr='dot1q';
               break;

           default:
               $wordstr='10000';
               break;
          }
        //构成命令行数组
        $data=[
            'switchport',
            $interType,
            'identity',
            $wordstr
           ];
        
        
  
        
         $arr[]=$this->format_arr($data);
        
         return $arr;
        
    }
}
