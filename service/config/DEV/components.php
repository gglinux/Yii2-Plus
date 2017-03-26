<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/26
 * Time: 上午10:00
 */

//////////////开发坏境数据库配置////////////////

$db_config = [
    'db_user' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=47.92.0.111;dbname=hjsk_user',
        'username' => 'boom',
        'password' => 'k9skkultSr&dFYP',
        'charset' => 'utf8mb4',
    ],
    'db_lianpa' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=192.168.1.14;dbname=boom',
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8',
    ],
];

return $db_config;