<?php

namespace app\admin\model\zhibocms\zbjk;

use think\Model;
use traits\model\SoftDelete;

class Mculist extends Model
{

   

    //数据库
    protected $connection = 'zbsql';
    // 表名
    protected $name = 'mcu_list';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
//    protected $createTime = 'createtime';
//    protected $updateTime = 'updatetime';
    //protected $deleteTime = 'delete_time';

    // 追加属性
//    protected $append = [
//        'starttime_text',
//        'stoptime_text'
//    ];
//    

 
    

}
