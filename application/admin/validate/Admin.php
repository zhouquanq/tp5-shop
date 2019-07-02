<?php
namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{
    protected $rule = [
        'adminname'  =>  'require|min:6|max:30',
        'passwd'  =>  'require|min:6|max:30',
    ];

    protected $message = [
        'adminname.require' => '用户名不能为空！',
        'adminname.min'     => '用户名不能少于6位！',
        'adminname.max'     => '用户名不能超过30位！',
        'passwd.require'  => '密码不能为空！',
        'passwd.min'      => '密码不能少于6位！',
        'passwd.max'      => '密码不能超过30位！',
    ];
}