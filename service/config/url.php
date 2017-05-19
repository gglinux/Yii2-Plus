<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/26
 * Time: 上午9:57
 */
///////////RPC对外路由配置/////////////

$rules = [
    //推送
    'push' =>'push/push/index',
    //用户
    'user' =>'user/user/index',
];

return $rules;