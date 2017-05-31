<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/26
 * Time: 上午9:57
 */
///////////admin对外路由配置/////////////

$rules = [
    //管理后台 用户模块
    'admin/user/loginbyemail'=> 'admin/user/loginbyemail',
    'admin/user/loginout'    => 'admin/user/loginout',
    'admin/user/info'   => 'admin/user/info',
    //测试文章 模块
    'article/list'      => 'test/article/list',
    'article/detail'    =>'test/article/detail'
];

return $rules;