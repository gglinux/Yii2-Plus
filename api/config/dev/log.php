<?php
return [
    [
        'class' => 'yii\log\FileTarget',
        'levels' => ['error', 'warning', 'info'],
        'logVars' => [],
    ],
    [
        'class' => 'yii\log\FileTarget',
        'levels' => ['error'],
        'logVars' => [],
        'logFile'=>'@runtime/logs/webservice.log',
        'maxFileSize'=>4096,
        'maxLogFiles'=>50,
        'categories' => [
            'application.service.*',
            'service.*',
            'thrift.*'
        ],

    ],
    [
        'class' => 'yii\log\FileTarget',
        'levels' => ['error', 'warning', 'info'],
        'logVars' => [],
        'logFile'=>'@runtime/logs/thrift.log',
        'maxFileSize'=>4096,
        'maxLogFiles'=>50,
        'categories' => [
            'thrift.*'
        ],

    ],
    [
        'class' => 'yii\log\FileTarget',
        'levels' => ['error', 'warning', 'info'],
        'logVars' => [],
        'logFile'=>'@runtime/logs/request.log',
        'maxFileSize'=>20480,
        'maxLogFiles'=>50,
        'categories' => [
            'application.service.request',
        ],

    ],
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