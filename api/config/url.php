<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/26
 * Time: 上午9:57
 */
///////////API对外路由配置/////////////

$rules = [
    'test'         =>'site/index',
    /////////////////账号中心//////////////////

    'user/register'     =>  '/user/register/index',
    'user/login'        =>  '/user/login/index',
];

return $rules;