<?php
namespace app\common\logic;


class AclLogic extends Logic{
    
    
    /*
     * 声明access-list
     * @$arr   拼接数组
     * @$oprate       操作
     * @$name  名称
     * 
     */
    public function loadACL(array $arr=[],$name='',$oprate='') {
        
        
        //构成命令行数组
        $data=[
            $oprate,
            'ip',
            'access-list',
            $name
           ];
        
        
  
        
         $arr[]=$this->format_arr($data);
        
         return $arr;
        
    }
    

    
    /*
     * 添加策略命令
     * @$arr   拼接数组
     * @$oprate       操作
     * @$id  id
     * @$pattern 模式，permit--允许;deny--禁止;remark--
     * @protocol 协议 ip,tcp,udp,vlan
     * @sourceAddr 源地址  
     *     单选如下：
     *         1.any
     *         2.host xxxx(提交过来不用加host)
     *         3.A.B.C.D
     *         4.A.B.C.D/E
     * @sourcePort 源端口   
     *    单选如下：
     *         1.eq/neq/lt/gt port1 port-n
     *         2.range port_1 port_2   之间
     * @$destAddr  目的地址  同源地址规则
     * @$destPort  目的端口  同源端口规则
     */
    public function opratePloy(array $arr=[],$oprate='',$id='',$pattern='',$protocol='',$sourceAddr='',$sourcePort='',$destAddr='',$destPort='') {
        
      
         //关于 协议的地址组合设置
         if($protocol=='ip'){
             $sourceAddr='host '.$sourceAddr;
             $destAddr='host '.$destAddr;
         }
        
         //构成命令行数组
        $data=[
            $oprate,
            $id,
            $pattern,
            $protocol,
            $sourceAddr,
            $sourcePort,
            $destAddr,
            $destPort
           ];
        
        
  
        $arr[]=$this->format_arr($data);
        
        return $arr;
       
        
    }

   
}
