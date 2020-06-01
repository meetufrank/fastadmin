<?php

namespace app\admin\validate\meetingroom\uname;

use think\Validate;

class Tms extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'title'=>"require|unique:meet_tms,title^uid",
        'name'=>"require|unique:meet_tms",
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
            'name'
            
        ],
        'edit' => [
            'title',
            'name'
            
        ],
    ];
    
    public function __construct(array $rules = [], $message = [], $field = []) {
        $this->message=[
            'name.unique'=>__('Name is used'),
            'title.unique'=>__('Title is used')
        ];
        
        
        parent::__construct($rules, $message, $field);
    }
    
}
