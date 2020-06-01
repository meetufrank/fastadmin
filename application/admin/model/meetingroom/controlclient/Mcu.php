<?php

namespace app\admin\model\meetingroom\controlclient;

use think\Model;
use traits\model\SoftDelete;

class Mcu extends Model
{

    use SoftDelete;

    

    // 表名
    protected $name = 'meet_mcu_list';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'status_text'
    ];
    

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }

    
    public function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }
    
    public function getProtocolList()
    {
        return ['http' => __('http'), 'https' => __('https')];
    }
    
    
    public function getProtocolTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['protocol']) ? $data['protocol'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
