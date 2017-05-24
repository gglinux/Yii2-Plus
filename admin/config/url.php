<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/26
 * Time: 上午9:57
 */
///////////admin对外路由配置/////////////

$rules = [
    //用户
    'admin/login/loginbyemail' =>'user/user/login',
];

return $rules;