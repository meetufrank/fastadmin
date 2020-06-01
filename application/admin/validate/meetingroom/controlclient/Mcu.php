<?php

namespace app\admin\validate\meetingroom\controlclient;

use think\Validate;

class Mcu extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'title'=>"require|unique:meet_mcu_list",
    ];
    /**
     * 提示消息
     */
    protected $message = [];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => [
            'title',
            
        ],
        'edit' => [
            'title',
            
        ],
    ];
    
    public function __construct(array $rules = [], $message = [], $field = []) {
        $this->message=[
            'title.unique'=>__('Title is used')
        ];
        
        
        parent::__construct($rules, $message, $field);
    }
    
}
