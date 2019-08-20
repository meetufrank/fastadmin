<?php

namespace app\admin\model\zhibocms\zbjk;

use think\Model;
use traits\model\SoftDelete;

class Hylist extends Model
{

  

    //数据库
    protected $connection = 'zbsql';
    // 表名
    protected $name = 'meeting_log';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
//    protected $createTime = 'createtime';
//    protected $updateTime = 'updatetime';
//    protected $deleteTime = 'delete_time';

    // 追加属性
    protected $append = [
        'starttime_text',
        'stoptime_text'
    ];
    

    protected static function init()
    {
        self::afterInsert(function ($row) {  //插入数据执行后
           
        });
    }

    

    public function getStarttimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['start_time']) ? $data['start_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getStoptimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['stop_time']) ? $data['stop_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }



    protected function setStarttimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setStoptimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    public function mcu()
    {
        return $this->belongsTo('Mculist', 'mcu_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
    
    public function channel() {
        return $this->belongsTo('Chlist', 'channel_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
