<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/26
 * Time: 上午10:00
 */

//////////////开发坏境数据库配置////////////////

$db_config = [
    'queue' => [
        'class' => \zhuravljov\yii\queue\redis\Queue::className(),
        'redis' => 'redis', // connection ID
        'channel' => 'queue', // queue channel
    ],
];

return $db_config;