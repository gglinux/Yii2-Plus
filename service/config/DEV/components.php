<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/26
 * Time: 上午10:00
 */

//////////////开发坏境数据库配置////////////////

const DEV_SERVER_IP = '47.92.0.111';
$db_config = [
    'db_user' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host='.DEV_SERVER_IP.';dbname=hjsk_user',
        'username' => 'boom',
        'password' => 'k9skkultSr&dFYP',
        'charset' => 'utf8mb4',
    ],
    'db_lianpa' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host='.DEV_SERVER_IP.';dbname=face_party',
        'username' => 'boom',
        'password' => 'k9skkultSr&dFYP',
        'charset' => 'utf8mb4',
    ],
    'redis' => [
        'class' => 'yii\redis\Connection',
        'hostname' => 'localhost',
        'port' => 6379,
        'database' => 0,
    ]
];

return $db_config;