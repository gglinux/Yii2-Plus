<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/26
 * Time: 上午10:00
 */

//////////////开发坏境数据库配置////////////////
const DEV_SERVER_IP = '127.0.0.1';


$db_config = [
    //用户数据库
    'db_user' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host='.DEV_SERVER_IP.';dbname=hjsk_user',
        'username' => 'user',
        'password' => '123456',
        'charset' => 'utf8mb4',
    ],
    //其他数据库
    'db_other其他数据库' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host='.DEV_SERVER_IP.';dbname=hjsk_user',
        'username' => 'other',
        'password' => '123456',
        'charset' => 'utf8mb4',
    ],

    'redis' => [
        'class' => 'yii\redis\Connection',
        'hostname' => 'localhost',
        'port' => 6379,
        'database' => 0,
    ],
    //其他Redis
    'redis_other' => [
        'class' => 'yii\redis\Connection',
        'hostname' => 'localhost',
        'port' => 6380,
        'database' => 0,
    ]
];

return $db_config;