<?php

namespace app\admin\model\meetingroom\sys;

use think\Model;
use traits\model\SoftDelete;

class Meetroom extends Model
{

    use SoftDelete;

    //数据库
    protected $connection = 'database';
    // 表名
    protected $name = 'meetroom';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'slmodel_text',
        'status_text'
    ];
    

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }

    
    public function getSlmodelList()
    {
        return ['1' => __('Slmodel 1'), '2' => __('Slmodel 2')];
    }

    public function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden')];
    }


    public function getSlmodelTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['slmodel']) ? $data['slmodel'] : '');
        $list = $this->getSlmodelList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




    public function meetbwidth()
    {
        return $this->belongsTo('app\admin\model\meetingroom\sys\Meetbwidth', 'meetbwidth_id', 'id', ['title'], 'LEFT')->setEagerlyType(0);
    }
}
