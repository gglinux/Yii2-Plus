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
    ],
    [
        'class' => 'yii\log\EmailTarget',
        'levels' => ['error'],
        'categories' => [
            'application.api.*',
            'api.*',
        ],
        'message' => [
            'from' => ['log@hjsk.com'],
            'to' => ['admin@hjsk.com', 'developer@hjsk.com'],
            'subject' => 'Database errors at hjsk.com',
        ],
    ],
    //请求分析日志
    [
        'class' => 'yii\log\FileTarget',
        'levels' => ['error', 'warning', 'info'],
        'logVars' => [],
        'logFile'=>'@runtime/logs/api_request.log',
        'maxFileSize'=>20480,
        'maxLogFiles'=>50,
        'categories' => [
            'application.api.request',
            'api.*',
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