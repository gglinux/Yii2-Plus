<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/26
 * Time: 上午10:07
 */

/////////////////开发坏境日志配置///////////////////
$log_config =  [
    [
        'class' => 'yii\log\FileTarget',
        'levels' => ['error', 'warning', 'info'],
        'logVars' => [],
    ],
    [
        'class' => 'yii\log\FileTarget',
        'levels' => ['error'],
        'logVars' => [],
        'logFile'=>'@runtime/logs/service_error.log',
        'maxFileSize'=>4096,//日志大小
        'maxLogFiles'=>50,//保存最大个数，Yii会按时间保留最近50个文件
        'categories' => [
            'application.service.*',
            'service.*',
        ],
    ],
    //请求分析日志
    [
        'class' => 'yii\log\FileTarget',
        'levels' => ['error', 'warning', 'info'],
        'logVars' => [],
        'logFile'=>'@runtime/logs/service_request.log',
        'maxFileSize'=>20480,
        'maxLogFiles'=>50,
        'categories' => [
            'application.service.request',
            'service.*',
        ],
    ],
    //性能分析日志
    [
        'class' => 'yii\log\FileTarget',
        'levels' => ['profile'],
        'logVars' => [],
        'logFile'=>'@runtime/logs/profile.log',
        'maxFileSize'=>4096,
        'maxLogFiles'=>50,
        'categories' => [],
    ],
];

return $log_config;