<?php
namespace app\common\logic;

use app\common\logic\AclLogic;  //ACL对象类

class AcLAppLogic extends Logic{
    
    
    
   

    /*
     * ACL添加一个或者多个策略
     * @$query_arr  拼接数组
     * @param $arr  array
     *     注意：
     *        可选：可以不配置，包括键值
     *        字段详细填写内容规范参考 ACL对象类
     *     详细示例：
     *$arr=[
     *      'aclname'=>'AIHUA',               //acl名称：必填
     *       'ploylist'=>[                    //策略列表是个二维数组，可以包含多个数组(策略)
     *          [    
     *           //第一个策略
     *              'id'=>10,                 //id：可选，默认为空
     *              'pattern'=>'permit',      //允许还是禁止:可选，默认permit
     *              'protocol'=>'ip',         //协议：可选，默认ip
     *              'sourceAddr'=>'1.1.1.0',  //源地址：必填
     *              'sourcePort'=>'any',      //源端口：可选,默认any
     *              'destAddr'=>'2.2.2.2',    //目的地址：必填
     *              'destPort'=>'any'         //目的端口：可选，默认any
     *          ],
     *          [
     *           //第二个策略
     *          ]
     *      ]
     *  ];
     */  
    public function addAclPloy(array $query_arr=[],array $arr) {
        
     
        return $this->opratePloy($query_arr, $arr, 'add');
        
        
    }
    
    /*
     * 删除一个或者多个策略
     * @$query_arr  拼接数组
     * @param $arr  array
     *     注意：
     *        可选：可以不配置，包括键值
     *        字段详细填写内容规范参考 ACL对象类
     *     详细示例：
     *  $arr=[
     *      'aclname'=>'AIHUA',               //acl名称:必填
     *       'ploylist'=>[                    //策略列表是个二维数组，可以包含多个数组(策略)
     *          [    
     *           //第一个策略
     *              'id'=>10,                 //id：必填
     *          ],
     *          [
     *           //第二个策略
     *          ]
     *      ]
     *  ];
     */
    public function deleteAclPloy(array $query_arr=[],array $arr) {
        
        
        return $this->opratePloy($query_arr, $arr, 'delete');
        
    }
    
    
    /*
     * 操作策略函数
     * @$query_arr  拼接数组
     * @$arr     参数数组
     * @$oprate 操作
     */
    private function opratePloy(array $query_arr,array $arr,$oprate='add') {
        
        
         $acl= AclLogic::getInstance();
         $name= !empty($arr['aclname'])?$arr['aclname']:'';
         
         $query=$acl->enable($query_arr);
         $query=$acl->configure($query);
         $query=$acl->loadACL($query,$name);
        
        if(is_array($arr['ploylist'])&& !empty($arr['ploylist'])){
            $ploylist=$arr['ploylist'];
            foreach (@$ploylist as $key => $value) {
                
                 if($oprate=='add'){   //添加
                     
                    $id= !empty($value['id'])?intval($value['id']):'';
                    $pattern= !empty($value['pattern'])?$value['pattern']:'permit';
                    $protocol=!empty($value['protocol'])?$value['protocol']:'ip';
                    $sourcePort=!empty($value['sourcePort'])?$value['sourcePort']:'';
                    $destPort=!empty($value['destPort'])?$value['destPort']:'';

                    $query=$acl->opratePloy($query,'',$id,$pattern,$protocol,$value['sourceAddr'],$sourcePort,$value['destAddr'],$destPort); //添加
                     
                  }elseif($oprate=='delete'){   //删除
                      
                       $id= !empty($value['id'])?intval($value['id']):'';
                       $query=$acl->opratePloy($query,'no',$id); //添加
                  }
               
            }
        }
        $query=$acl->end($query);
        
        return $query;
    }
    
   
}
