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