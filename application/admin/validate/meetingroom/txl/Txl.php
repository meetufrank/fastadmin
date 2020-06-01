<?php

namespace app\admin\validate\meetingroom\txl;

use think\Validate;

class Txl extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'email'=>"unique:meet_txl"
    ];
    /**
     * 提示消息
     */
    protected $message = [
        'email.unique'=>'邮箱必须唯一'
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => [
            'email'
        ],
        'edit' => [
            'email'
        ],
    ];
    
}
