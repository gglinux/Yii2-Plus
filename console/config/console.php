<?php

$params = require(__DIR__ . '/../../service/config/params.php');

$ENV_CONFIG_PATH = __DIR__.'/'.YII_ENV; //当前环境配置所在目录

Yii::setAlias('@service', dirname(dirname(__DIR__)) . '/service');
Yii::setAlias('@common', dirname(dirname(__DIR__)) . '/common');

Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');

$config = [
    'id' => 'service',
    'basePath' => dirname(__DIR__),
    'timeZone'=>'Asia/Chongqing',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'controllerNamespace' => 'console\commands',
    'bootstrap' => ['log'],
    'components' => array_merge([

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => require(__DIR__.'/log.php'),
        ],

    ], require(__DIR__.'/components.php')),
    //'modules' => require(__DIR__ . '/modules.php'),
    'params' => $params,
];
if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '11.11.11.1', '192.168.11.1', '::1'],
    ];

}
//var_dump($config);exit();
return $config;
