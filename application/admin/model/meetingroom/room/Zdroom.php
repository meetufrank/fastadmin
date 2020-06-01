<?php

namespace app\admin\model\meetingroom\room;

use think\Model;
use traits\model\SoftDelete;

class Zdroom extends Model
{

    use SoftDelete;

    

    // 表名
    protected $name = 'meet_zd_room';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'status_text',
        'fenlei_text'
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
    
    public function getFenleiList()
    {
        return ['local' => __('Local'), 'other' => __('Other')];
    }


    public function getFenleiTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['fenlei']) ? $data['fenlei'] : '');
        $list = $this->getFenleiList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    
    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




    public function bandwidth()
    {
        return $this->belongsTo('app\admin\model\meetingroom\Bandwidth', 'meet_bandwidth_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
