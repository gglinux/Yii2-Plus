<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/27
 * Time: 下午5:18
*/

return [
    'adminEmail'    => 'admin@example.com',
    //API接口请求 有效期 10min
    'nonceTime'     => 60*10,
    //jwt生成secret
    'jwtKey'        => '',
    //RPC 接口
    'test_rpc' =>'http://service.com/test',
    'user_rpc' =>'http://service.dev.dabaozha.com/user',

    //签名secret
    'secretKey' =>[
        'lianpa' => 'U5hM&qrtF5NSRY#K'
    ]
];
